<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 15/02/18
 * Time: 1:27 PM
 */

namespace App\Services;


use App\Content\Category;
use App\Content\Schema;
use App\Repositories\SchemaRepository;
use Illuminate\Validation\Rule;

class ValidationRulesService
{

    /**
     * @var SchemaRepository
     */
    protected $schemaRepository;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $input = [];

    /**
     * @var bool
     */
    protected $strict = true;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var array
     */
    protected $fieldCategoryMap = [];

    public function __construct(SchemaRepository $schemaRepository)
    {
        $this->schemaRepository = $schemaRepository;
    }

    /**
     * @param array $input
     * @param string $prefix
     * @return array
     */
    public function getContentRules(array $input, string $prefix = 'content', bool $strict = true)
    {
        $this->setInput($input);

        $this->setPrefix($prefix);

        if ($this->ruleSchemaVersion() === false) {
            return $this->getRules();
        }

        $this->runCategoryRules();

        return $this->getRules();
    }

    protected function setInput(array $input)
    {
        $this->input = $input;
    }

    protected function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    protected function setStrict(bool $strict)
    {
        $this->strict = $strict;
    }

    /**
     * Try to parse the schema version and category slugs, if not return false
     *
     * @return bool
     */
    protected function ruleSchemaVersion()
    {
        $schemaVersion = (string) $this->getInputByKey('schema_version');

        $schema = $this->schemaRepository->parseContent()->getSchemaByVersion($schemaVersion);

        if ($schema === false) {
            $this->setRule('schema_version', ['required', 'string', Rule::in($this->schemaRepository->getVersionsArray())]);

            return false;
        }

        $this->setRule('schema_version', ['required', 'string', Rule::in([$schemaVersion])]);

        $this->schema = $schema;

        $categorySlug = (string) $this->getInputByKey('category_slug');
        $category = $this->schema->getCategoryBySlug($categorySlug);

        if ($category === false) {
            $this->setRule('category_slug', ['required', 'string', Rule::in($this->schema->getCategories()->getSlugsArray())]);

            return false;
        }

        $this->setRule('category_slug', ['required', 'string', Rule::in([$categorySlug])]);

        $this->category = $category;

        return true;
    }

    /**
     * Read through the given category's field definitions to convert them into
     * Laravel Validator-compatible rules
     *
     * @return void
     */
    protected function runCategoryRules()
    {
        $this->fieldCategoryMap = $this->category->getFieldCategoriesToFieldsMap();

        // only valid field types can be used
        $this->setRule('fields.*.slug', ['string', 'required', Rule::in($this->category->getFields()->getSlugsArray())]);

        // require each items list to be an array
        $this->setRule('fields.*.items', ['array']);

        foreach ($this->category->getFields() as $field) {
            $fieldKey = 'fields.' . $field->getSlug() . '.items.*.value';
            $ruleArray = [];

            if ($this->strict) {
                // make sure field required rule is respected
                // or make sure field category at least one rule is respected
                if ($field->isRequired()) {
                    $ruleArray[] = 'required';
                } elseif ($this->category->getFieldCategories()->isAtLeastOne($field->getCategory()) === true) {
                    $siblingFields = $this->fieldCategoryMap[$field->getCategory()];
                    array_forget($siblingFields, [array_search($field->getSlug(), $siblingFields)]);

                    $ruleArray[] = 'required_without_all:' . implode(',', $siblingFields);
                }
            }

            switch ($field->getFilter()) {
                case 'phone':
                    $ruleArray[] = 'phone:' . $this->getFullFieldKey('fields.' . $field->getSlug() . '.items.*.attributes.country_code');
                    $this->setRule('fields.' . $field->getSlug() . '.items.*.attributes.country_code', ['required_with:' . $this->getFullFieldKey($fieldKey)]);
                    break;
                case 'url':
                    $ruleArray[] = 'url';
                    break;
                case 'email':
                    $ruleArray[] = 'email';
                    break;
                case 'paragraph':
                    $ruleArray[] = 'string';
                    break;
                case 'free_text':
                    $ruleArray[] = 'string';
                    break;
            }

            $this->setRule($fieldKey, $ruleArray);
        }
    }

    /**
     * @param string $key
     * @return array|mixed|null
     */
    protected function getInputByKey(string $key)
    {
        $keyTree = [];

        if ($this->prefix) {
            $keyTree[] = $this->prefix;
        }

        $exploded = explode('.', $key);

        foreach ($exploded as $next) {
            $keyTree[] = $next;
        }

        $search = $this->input;

        $index = 0;

        while($index < count($keyTree)) {
            if (is_array($search) === false) {
                return null;
            }

            $search = $search[$index];

            $index++;
        }

        return $search;
    }

    protected function getRules()
    {
        return $this->rules;
    }

    protected function getFullFieldKey(string $fieldKey)
    {
        if ($this->prefix) {
            return $this->prefix . '.' . $fieldKey;
        }

        return $fieldKey;
    }

    protected function setRule(string $fieldKey, array $rule)
    {
        $newKey = $this->getFullFieldKey($fieldKey);

        $this->rules[$newKey] = $rule;
    }

}