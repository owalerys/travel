<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:28 PM
 */

namespace App\Content\Field;


class Category
{
    private $title;

    private $slug;

    private $isEndCategory = true;

    private $categories;

    public function __construct($config)
    {
        $this->setTitle($config['title']);
        $this->setSlug($config['slug']);

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

    private function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    private function setIsEndCategory(bool $isEndCategory)
    {
        $this->isEndCategory = $isEndCategory;
    }

    private function setCategories(Category\Collection $categories)
    {
        $this->categories = $categories;
    }
}