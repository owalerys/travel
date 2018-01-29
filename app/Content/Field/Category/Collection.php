<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:28 PM
 */

namespace App\Content\Field\Category;


use App\Content\Field\Category;

class Collection extends \App\Content\Base\Collection
{

    public function __construct($config)
    {
        foreach ($config as $slug => $category) {
            $this->push(new Category($category));
        }
    }

}