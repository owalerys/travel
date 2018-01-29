<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 9:49 AM
 */

namespace App\Content;


class Category
{

    /** @var string */
    private $title;

    /** @var string */
    private $slug;

    /** @var array|string */
    private $type = ['content', 'link'];

    /** @var Category\Collection */
    private $categories;

    /** @var string */
    private $association = 'airline';

    /** @var bool */
    private $isEndArticle = false;

    /** @var Field\Collection */
    private $fields;

    /** @var Field\Category\Collection */
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

    private function setSlug(string $slug)
    {
        $this->slug = $slug;
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

}