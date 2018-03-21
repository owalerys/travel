<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 9:49 AM
 */

namespace App\Content;


use App\Content\Base\Entity;

class Category extends Entity
{

    /** @var string */
    private $title;

    /** @var array|string */
    private $type = ['content', 'link'];

    /** @var Category\Collection */
    private $categories;

    /** @var string */
    private $association = 'airline';

    /** @var bool */
    private $isEndArticle = false;

    /** @var bool */
    private $acceptNewSubmissions = false;

    /** @var Field\Collection|Field[] */
    private $fields;

    /** @var Field\Category\Collection|Field\Category[] */
    private $fieldCategories;

    /**
     * ArticleCategory constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->setTitle($config['title']);
        $this->setSlug($config['slug']);

        if (!empty($config['type'])) {
            $this->setIsEndArticle(true);

            $this->setAssociation($config['association']);

            $this->setType($config['type']);

            $this->setFields(new Field\Collection($config['fields']));

            $this->setFieldCategories(new Field\Category\Collection($config['field_categories']));

            if (isset($config['accept_new_submissions'])) {
                $this->setAcceptNewSubmissions($config['accept_new_submissions']);
            }
        } else {
            $this->setIsEndArticle(false);

            $this->setCategories(new Category\Collection($config['categories']));
        }

        return $this;
    }

    private function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param $type
     * @throws \Exception
     */
    private function setType($type)
    {
        $this->validateStringArrayField($type, ['content', 'link']);

        $this->type = $type;
    }

    private function setCategories(Category\Collection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param string $association
     * @throws \Exception
     */
    private function setAssociation($association)
    {
        $this->validateStringArrayField($association, ['airline', '']);

        $this->association = $association;
    }

    private function setIsEndArticle(bool $isEndArticle)
    {
        $this->isEndArticle = $isEndArticle;
    }

    private function setAcceptNewSubmissions(bool $acceptNewSubmissions)
    {
        $this->acceptNewSubmissions = $acceptNewSubmissions;
    }

    private function setFields(Field\Collection $fields)
    {
        $this->fields = $fields;
    }

    private function setFieldCategories(Field\Category\Collection $fieldCategories)
    {
        $this->fieldCategories = $fieldCategories;
    }

    /**
     * @param $options
     * @param $input
     * @throws \Exception
     */
    private function validateStringArrayField($input, $options)
    {
        if (is_string($input)) {
            if (in_array($input, $options) === false) {
                throw new \Exception('String option must be valid in potential options array, given: ' . $input . ' for ' . $this->slug);
            }
        } elseif (is_array($input)) {
            foreach ($input as $test) {
                if (is_string($test) === false) {
                    throw new \Exception('Must be array of strings if array for ' . $this->slug);
                }

                if (in_array($test, $options) === false) {
                    throw new \Exception('String option must be valid in potential options array, given: ' . $input . ' for ' . $this->slug);
                }
            }
        } else {
            throw new \Exception('Field must be array or string, given: ' . $input . ' for ' . $this->slug);
        }
    }

    public function getSlugsArray()
    {
        $slugs = [ $this->slug ];

        if ($this->isEndArticle) {
            return $slugs;
        } else {
            return array_merge($slugs, $this->categories->getSlugsArray());
        }
    }

    public function getCategoryDefinitionForSlug(string $slug)
    {
        if ($this->isEndArticle) {
            return ($slug === $this->slug) ? $this : false;
        } else {
            return $this->categories->getCategoryBySlug($slug);
        }
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFieldCategories()
    {
        return $this->fieldCategories;
    }

    public function getFieldCategoriesToFieldsMap()
    {
        $map = [];

        foreach ($this->fieldCategories as $fieldCategory) {
            $map[$fieldCategory->getSlug()] = [];
        }

        foreach ($this->fields as $field) {
            if ($field->getCategory()) {
                $map[$field->getCategory()][] = $field->getSlug();
            }
        }

        return $map;
    }

    public function isContentOnly()
    {
        return $this->type === 'content';
    }

    public function isLinkOnly()
    {
        return $this->type === 'link';
    }

    public function canContentOrLink()
    {
        return is_array($this->type) && in_array('content', $this->type) && in_array('link', $this->type);
    }

    /**
     * @return array
     */
    public function getTypeArray()
    {
        if (is_string($this->type) === true) {
            return [$this->type];
        } else {
            return $this->type;
        }
    }

}