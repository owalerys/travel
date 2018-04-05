<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleIndex;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ArticleVersion;

class VersionController extends Controller
{

    public function fork($article, $version)
    {
        $this->authorize('create', ArticleVersion::class);

        $articleVersion = ArticleVersion::where('article_id', $article)->where('version', $version)->with(['article.versions'])->first();

        if (!$articleVersion) {
            abort(404);
        }

        $newVersion = new ArticleVersion;

        $newVersion->article_id = $articleVersion->article_id;
        $newVersion->parent_id = $articleVersion->id;
        $newVersion->title = $articleVersion->title;
        $newVersion->description = $articleVersion->description;
        $newVersion->type = $articleVersion->type;
        $newVersion->url = $articleVersion->url;
        $newVersion->content = $articleVersion->content;
        $newVersion->version = $articleVersion->article->versions->max('version') + 1;
        $newVersion->schema_version = $articleVersion->schema_version;
        $newVersion->category_slug = $articleVersion->category_slug;

        $newVersion->author()->associate(\Auth::user());

        $newVersion->create_date = Carbon::now()->format('Y-m-d H:i:s');

        $newVersion->save();

        return response()->json($newVersion);
    }

    public function archive($article, $version)
    {
        $articleVersion = ArticleVersion::where('article_id', $article)->where('version', $version)->first();

        if (!$articleVersion) {
            abort(404);
        }

        $this->authorize('archive', $articleVersion);

        $articleVersion->status = ArticleVersion::STATUS_ARCHIVED;
        $articleVersion->save();

        return response()->json($articleVersion);
    }

    public function update($article, $version, CreateArticleIndex $request)
    {
        $articleVersion = ArticleVersion
            ::where('article_id', '=', $article)
            ->where('id', '=', $version)
            ->with(['article.versions', 'author'])
            ->first();

        // Update the main article if there is only one version
        if ($articleVersion->article->versions->count() === 1) {
            $article = $articleVersion->article;

            $article->display_title = $request->input('content.title');
            $article->display_description = $request->input('content.description');
            $article->type = $request->input('content.type');
            $article->schema_version = $request->input('content.schema_version');
            $article->category_slug = $request->input('content.category_slug');

            $article->save();
        }

        $articleVersion->title = $request->input('content.title');
        $articleVersion->description = $request->input('content.description');
        $articleVersion->type = $request->input('content.type');
        $articleVersion->url = $request->input('content.url');
        $articleVersion->schema_version = $request->input('content.schema_version');
        $articleVersion->category_slug = $request->input('content.category_slug');
        $articleVersion->content = json_encode($request->input('content'));

        $articleVersion->save();

        return response()->json($articleVersion);
    }

    public function retrieve($article, $version)
    {

    }

    public function status($article, $version)
    {

    }

}
