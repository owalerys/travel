<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 05/02/18
 * Time: 3:10 PM
 */

namespace App\Repositories;


use App\Airline;
use App\Article;
use Illuminate\Support\Facades\DB;

class ContentRepository extends Repository
{

    protected $schemaRepository;

    public function __construct(SchemaRepository $schemaRepository)
    {
        $this->schemaRepository = $schemaRepository;
    }

    public function getLiveArticles()
    {
        return $liveArticles = Article::where('live', '=', '1')->get();
    }

    public function getActiveArticles()
    {
        return $activeArticles = Article::where('active', '=', '1')->get();
    }

    public function getArticles()
    {
        return $articles = Article::all();
    }

    public function getAirlinesWithContent()
    {
        return $liveAirlines = Airline::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('articles')
                ->whereRaw('articles.airline_id = airlines.id');
        })
            ->get();
    }

    public function getActiveAirlines()
    {
        return $activeAirlines = Airline::where('active', '=', 1)->get();
    }

    protected function getContentStructure()
    {
        return $this->schemaRepository->parseContent();
    }

    public function getContent()
    {

    }

}