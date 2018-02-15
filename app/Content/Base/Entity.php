<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:27 PM
 */

namespace App\Content\Base;


abstract class Entity
{

    /** @var string */
    protected $slug;

    protected function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param string $slug
     * @throws \Exception
     */
    public function validateSlug(string $slug)
    {
        if ($slug !== $this->slug) {
            throw new \Exception('Slugs do not match : ' . $this->slug . ', ' . $slug);
        }
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}