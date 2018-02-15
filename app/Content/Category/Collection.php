<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:17 PM
 */

namespace App\Content\Category;

use \App\Content\Base\Collection as BaseCollection;
use App\Content\Category;

class Collection extends BaseCollection
{
    /**
     * Collection constructor.
     * @param array $categoriesDefinition
     * @throws \Exception
     */
    public function __construct(array $categoriesDefinition)
    {
        foreach ($categoriesDefinition as $slug => $category) {
            $new = new \App\Content\Category($category);

            $new->validateSlug($slug);

            $this->push($new);
        }
    }

    public function getSlugsArray()
    {
        $slugs = [];

        /** @var Category $category */
        foreach ($this as $category) {
            $slugs = array_merge($slugs, $category->getSlugsArray());
        }

        return $slugs;
    }

    public function getCategoryBySlug(string $slug)
    {
        /** @var Category $category */
        foreach ($this as $category) {
            $search = $category->getCategoryDefinitionForSlug($slug);

            if ($search instanceof Category) {
                return $search;
            }
        }

        return false;
    }
}