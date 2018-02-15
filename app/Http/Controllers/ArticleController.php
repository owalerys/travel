<?php

namespace App\Http\Controllers;

use App\Airline;
use App\Http\Requests\CreateArticleIndex;
use App\Services\ArticleManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function create(CreateArticleIndex $request, ArticleManagementService $articleManagementService)
    {
        $article = $articleManagementService->createNewArticleWithFirstVersion(
            $request->input('title'),
            $request->input('category_slug'),
            Auth::user(),
            Airline::find($request->input('airline_id')),
            $request->input('description')
        );

        return response()->json($article->toArray());
    }

    public function status($article)
    {

    }

    public function retrieve($article)
    {

    }

}
