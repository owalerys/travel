<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:20 PM
 */

namespace App\Content\Base;


abstract class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var Entity[]
     */
    protected $collection = [];

    protected function push($item)
    {
        array_push($this->collection, $item);

        $this->afterPush($item);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    public function count()
    {
        return count($this->collection);
    }

    protected function afterPush($pushedItem)
    {
        // placeholder
    }

    public function getSlugsArray()
    {
        $slugs = [];

        foreach ($this->collection as $item)
        {
            $slugs[] = $item->getSlug();
        }

        return $slugs;
    }
}