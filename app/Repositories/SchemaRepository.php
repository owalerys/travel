<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 05/02/18
 * Time: 3:17 PM
 */

namespace App\Repositories;


use App\Content\Schema;

class SchemaRepository
{

    /**
     * @return Schema\Collection
     */
    public function parseContent()
    {
        return new Schema\Collection($this->getSchema());
    }

    /**
     * @return array
     */
    public function getSchema()
    {
        return config('content.schemas');
    }

    /**
     * @param $version string
     * @return string[]
     */
    public function getSlugsArray(string $version)
    {
        $schema = $this->parseContent()->getSchemaByVersion($version);

        if ($schema instanceof Schema === false) {
            return [];
        } else {
            return $schema->getCategories()->getSlugsArray();
        }
    }

    /**
     * @return string[]
     */
    public function getVersionsArray()
    {
        return $this->parseContent()->getVersionsArray();
    }

    /**
     * @param string $version
     * @param string $slug
     * @return \App\Content\Category|false
     */
    public function getCategoryBySlug(string $version, string $slug)
    {
        $schema = $this->parseContent()->getSchemaByVersion($version);

        if ($schema instanceof Schema) {
            return $schema->getCategories()->getCategoryBySlug($slug);
        } else {
            return false;
        }
    }

}