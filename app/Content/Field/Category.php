<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:28 PM
 */

namespace App\Content\Field;


use App\Content\Base\Entity;

class Category extends Entity
{
    private $title;

    private $isEndCategory = true;

    private $categories;

    private $atLeastOne = false;

    public function __construct($config)
    {
        $this->setTitle($config['title']);
        $this->setSlug($config['slug']);

        if (!empty($config['at_least_one'])) {
            $this->setAtLeastOne($config['at_least_one']);
        }

        if (!empty($config['categories'])) {
            $this->setIsEndCategory(false);

            $this->setCategories(new Category\Collection($config['categories']));
        }

        return $this;
    }

    private function setTitle(string $title)
    {
        $this->title = $title;
    }

    private function setIsEndCategory(bool $isEndCategory)
    {
        $this->isEndCategory = $isEndCategory;
    }

    private function setCategories(Category\Collection $categories)
    {
        $this->categories = $categories;
    }

    private function setAtLeastOne(bool $atLeastOne)
    {
        $this->atLeastOne = $atLeastOne;
    }
}