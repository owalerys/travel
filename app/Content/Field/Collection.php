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

    /**
     * Collection constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        foreach ($config as $slug => $field) {
            $new = new Field($field);

            $new->validateSlug($slug);

            $this->push($new);
        }
    }
}