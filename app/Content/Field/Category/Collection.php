<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:28 PM
 */

namespace App\Content\Field\Category;


use App\Content\Field;
use App\Content\Field\Category;

class Collection extends \App\Content\Base\Collection
{
    /**
     * @var bool[]
     */
    protected $atLeastOneMap = [];

    /**
     * Collection constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        foreach ($config as $slug => $category) {
            $new = new Category($category);

            $new->validateSlug($slug);

            $this->push($new);
        }
    }

    /**
     * @param $pushedItem Field\Category
     */
    protected function afterPush($pushedItem)
    {
        $this->atLeastOneMap[$pushedItem->getSlug()] = $pushedItem->isAtLeastOne();
    }

    /**
     * @param string $fieldCategorySlug
     * @return bool|null
     */
    public function isAtLeastOne(string $fieldCategorySlug)
    {
        if (isset($this->atLeastOneMap[$fieldCategory->getSlug()])) {
            return $this->atLeastOneMap[$fieldCategory->getSlug()];
        }

        return null;
    }

}