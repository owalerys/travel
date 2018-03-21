<?php

namespace App\Http\Controllers;

use App\Airline;
use App\Repositories\ContentRepository;
use App\Repositories\SchemaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Propaganistas\LaravelIntl\Facades\Country;

class ContentController extends Controller
{

    public function schema(SchemaRepository $schemaRepository)
    {
        return response()->json([
            'schemas' => $schemaRepository->getSchema()
        ]);
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

    public function airlines(ContentRepository $contentRepository)
    {
        return response()->json(['airlines' => Airline::all()]);
    }

    public function articles(ContentRepository $contentRepository)
    {
        return response()->json($contentRepository->getArticles());
    }

    public function activeArticles(ContentRepository $contentRepository, Request $request)
    {
        $request->validate([
            'airline_id' => 'required|exists:airlines,id'
        ]);

        return response()->json($contentRepository->getActiveArticles($request->input('airline_id')));
    }

    public function countries()
    {
        return response()->json([
            'countries' => Country::all()
        ]);
    }

}
