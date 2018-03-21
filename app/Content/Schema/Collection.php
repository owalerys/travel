<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 13/02/18
 * Time: 2:28 PM
 */

namespace App\Content\Schema;


use App\Content\Schema;

class Collection extends \App\Content\Base\Collection
{

    /**
     * Collection constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        foreach ($config as $version => $schema)
        {
            $new = new Schema($schema);

            $new->validateSlug($version);

            $this->push($new);
        }
    }

    public function getVersionsArray()
    {
        $versions = [];

        /** @var Schema $schema */
        foreach ($this as $schema) {
            $versions[] = $schema->getVersion();
        }

        return $versions;
    }

    public function getSchemaByVersion(string $version)
    {
        /** @var Schema $schema */
        foreach ($this as $schema)
        {
            if ($schema->getVersion() === $version) {
                return $schema;
            }
        }

        return false;
    }

    public function getCategoryBySlug(string $version, string $slug)
    {
        $schema = $this->getSchemaByVersion($version);

        if ($schema instanceof Schema) {
            return $schema->getCategories()->getCategoryBySlug($slug);
        } else {
            return false;
        }
    }

}