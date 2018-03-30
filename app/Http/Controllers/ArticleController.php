<?php

namespace App\Http\Controllers;

use App\Airline;
use App\Article;
use App\Services\ArticleManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function create(Request $request, ArticleManagementService $articleManagementService)
    {
        $request->validate([
            'title' => 'string|max:255|nullable',
            'category_slug' => 'string|required|max:255',
            'schema_version' => 'string|required|max:255',
            'url' => 'url|nullable|required_if:type,link',
            'type' => 'required|string|max:255',
            'description' => 'string|nullable',
            'airline_id' => 'required|exists:airlines,id'
        ]);

        $article = $articleManagementService->createNewArticleWithFirstVersion(
            $request->input('title'),
            $request->input('category_slug'),
            $request->input('schema_version'),
            $request->input('type'),
            $request->input('url'),
            Auth::user(),
            Airline::find($request->input('airline_id')),
            $request->input('description')
        );

        return response()->json($article->toArray());
    }

    public function status($article)
    {

    }

    public function retrieve($article, Request $request)
    {
        $retrievedArticle = Article::with(['topic', 'versions.author', 'versions.media'])->find($article);

        if (!$retrievedArticle) {
            return response('Article not found', 404);
        }

        return response()->json($retrievedArticle);
    }

}
