<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:17 PM
 */

namespace App\Content\Category;

use \App\Content\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    public function __construct(array $categoriesDefinition)
    {
        foreach ($categoriesDefinition as $slug => $category) {
            $this->push(new \App\Content\Category($category));
        }
    }
}