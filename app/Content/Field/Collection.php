<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:17 PM
 */

namespace App\Content\Field;

use \App\Content\Base\Collection as BaseCollection;
use App\Content\Field;

class Collection extends BaseCollection
{
    public function __construct($config)
    {
        foreach ($config as $slug => $field) {
            $this->push(new Field($field));
        }
    }
}