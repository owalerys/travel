<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 13/02/18
 * Time: 2:03 PM
 */

namespace App\Content;


use App\Content\Base\Entity;

class Schema extends Entity
{

    /** @var Category\Collection */
    protected $categories;

    public function __construct($config)
    {
        $this->setVersion($config['version']);
        $this->setCategories(new Category\Collection($config['categories']));
    }

    protected function setCategories(Category\Collection $categories)
    {
        $this->categories = $categories;
    }

    protected function setVersion(string $version)
    {
        $this->setSlug($version);
    }

    public function getVersion()
    {
        return $this->getSlug();
    }

    public function getCategoryBySlug(string $slug)
    {
        return $this->categories->getCategoryBySlug($slug);
    }

    /**
     * @return Category\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

}