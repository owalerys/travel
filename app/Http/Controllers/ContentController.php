<?php

namespace App\Http\Controllers;

use App\Repositories\ContentRepository;
use App\Repositories\SchemaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Propaganistas\LaravelIntl\Facades\Country;

class ContentController extends Controller
{

    public function schema(SchemaRepository $schemaRepository)
    {
        return response()->json($schemaRepository->getSchema());
    }

    public function contentAirlines(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getAirlinesWithContent());
    }

    public function activeAirlines(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getActiveAirlines());
    }

    public function liveArticles(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getLiveArticles());
    }

    public function articles(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getArticles());
    }

    public function activeArticles(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getActiveArticles());
    }

    public function countries()
    {
        return response()->json(Country::all());
    }

}
