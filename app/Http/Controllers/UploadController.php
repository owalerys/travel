<?php

namespace App\Http\Controllers;

use App\ArticleVersion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;

class UploadController extends Controller
{

    public function create($article, $version, Request $request)
    {
        /** @var ArticleVersion $articleVersion */
        $articleVersion = ArticleVersion::where('article_id', '=', $article)->where('id', '=', $version)->first();

        $this->authorize('upload', $articleVersion);

        $request->validate([
            'file' => 'required|file|mimes:pdf'
        ]);

        $media = $articleVersion->addMediaFromRequest('file')->toMediaCollection('default', 'uploads');

        return response()->json($media);
    }

    public function delete($article, $version, $file)
    {
        $articleVersion = ArticleVersion::where('article_id', '=', $article)
            ->where('id', '=', $version)
            ->with(['author', 'media'])
            ->first();

        $this->authorize('upload', $articleVersion);

        if (!$media = $articleVersion->media->firstWhere('id', $file)) {
            abort(404);
        }

        $media->delete();

        return response()->json(['message' => 'File deleted.']);
    }

    public function retrieve($article, $version, $file)
    {
        $articleVersion = ArticleVersion::where('article_id', '=', $article)
            ->where('id', '=', $version)
            ->with(['author', 'media'])
            ->first();

        $this->authorize('download', $articleVersion);

        /** @var $media Media */
        if (!$media = $articleVersion->media->firstWhere('id', $file)) {
            abort(404);
        }

        $url = $media->getTemporaryUrl(Carbon::now()->addMinutes(5));

        return response()->json(['url' => $url]);
    }

    public function update($article, $version, $file, Request $request)
    {
        $articleVersion = ArticleVersion::where('article_id', '=', $article)
            ->where('id', '=', $version)
            ->with(['author', 'media'])
            ->first();

        $this->authorize('upload', $articleVersion);

        /** @var $media Media */
        if (!$media = $articleVersion->media->firstWhere('id', $file)) {
            abort(404);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'internal' => 'required|boolean'
        ]);

        if ($title = $request->input('title')) {
            $media->setCustomProperty('title', $title);
        } else {
            $media->forgetCustomProperty('title');
        }

        if ($description = $request->input('description')) {
            $media->setCustomProperty('description', $description);
        } else {
            $media->forgetCustomProperty('description');
        }

        $media->setCustomProperty('internal', (bool) $request->input('internal'));

        $media->save();

        return response()->json($media);
    }

}
