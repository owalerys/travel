<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 9:50 AM
 */

namespace App\Content;


use App\Content\Base\Entity;

class Field extends Entity
{
    /** @var string */
    private $title;

    /** @var string */
    private $filter;

    /** @var bool */
    private $multiple = false;

    /** @var bool */
    private $customTitle = false;

    /** @var bool */
    private $customSubHeading = false;

    /** @var bool */
    private $required = true;

    /** @var bool */
    private $additionalInfo = false;

    /** @var string */
    private $category;

    public function __construct($config)
    {
        $this->setTitle($config['title']);
        $this->setSlug($config['slug']);
        $this->setFilter($config['filter']);
        if (!empty($config['multiple'])) {
            $this->setMultiple($config['multiple']);
        }
        if (!empty($config['custom_title'])) {
            $this->setCustomTitle($config['custom_title']);
        }
        if (!empty($config['custom_sub_heading'])) {
            $this->setCustomSubHeading($config['custom_sub_heading']);
        }
        if (!empty($config['required'])) {
            $this->setRequired($config['required']);
        }
        if (!empty($config['additional_info'])) {
            $this->setAdditionalInfo($config['additional_info']);
        }
        if (!empty($config['category'])) {
            $this->setCategory($config['category']);
        }

        return $this;
    }

    private function setTitle(string $title)
    {
        $this->title = $title;
    }

    private function setFilter(string $filter)
    {
        $this->filter = $filter;
    }

    private function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;
    }

    private function setCustomTitle(bool $customTitle)
    {
        $this->customTitle = $customTitle;
    }

    private function setCustomSubHeading(bool $customSubHeading)
    {
        $this->customSubHeading = $customSubHeading;
    }

    private function setRequired(bool $required)
    {
        $this->required = $required;
    }

    private function setAdditionalInfo(bool $additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }

    private function setCategory(string $categorySlug)
    {
        $this->category = $categorySlug;
    }

}