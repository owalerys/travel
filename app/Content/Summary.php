<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 05/02/18
 * Time: 3:27 PM
 */

namespace App\Content;


use App\Content\Category\Collection;

class Summary
{

    protected $schema;

    protected $activeAirlines;

    protected $activeTopics;

    public function __construct(Collection $schema, \Illuminate\Database\Eloquent\Collection $airlines, \Illuminate\Database\Eloquent\Collection $articles)
    {
        $this->setSchema($schema);
        $this->setActiveAirlines($airlines);
        $this->setActiveTopics($articles);
    }

    public function setSchema(Collection $schema)
    {
        $this->schema = $schema;
    }

    public function setActiveAirlines(\Illuminate\Database\Eloquent\Collection $airlines)
    {
        $this->activeAirlines = $airlines;
    }

    public function setActiveTopics(\Illuminate\Database\Eloquent\Collection $topics)
    {
        $this->activeTopics = $topics;
    }

}