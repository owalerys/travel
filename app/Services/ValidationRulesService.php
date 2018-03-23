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

/**
 * Generate the Laravel Validator rules to validate article content based on the given schema
 *
 * Class ValidationRulesService
 * @package App\Services
 */
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

    /**
     * ValidationRulesService constructor.
     * @param SchemaRepository $schemaRepository
     */
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
        $this->strict = $strict;

        $this->setInput($input);

        $this->setPrefix($prefix);

        if ($this->ruleSchemaVersion() === false) {
            return $this->getRules();
        }

        $this->runTopContentAttributes();

        $this->runCategoryRules();

        return $this->getRules();
    }

    /**
     * @param array $input
     */
    protected function setInput(array $input)
    {
        $this->input = $input;
    }

    /**
     * @param string $prefix
     */
    protected function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param bool $strict
     */
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

    protected function runTopContentAttributes()
    {
        $this->setRule('title', ['string', 'max:255', 'nullable']);
        $this->setRule('description', ['string', 'nullable']);

        $this->setRule('type', ['required', 'string', Rule::in($this->category->getTypeArray())]);

        if ($this->category->isLinkOnly()) {
            $this->setRule('url', ['url', 'string', 'required']);
        } else {
            $this->setRule('url', ['url', 'string', 'nullable']);
        }
    }

    /**
     * Read through the given category's field definitions to convert them into
     * Laravel Validator-compatible rules
     *
     * Not proud of this function, but it'll work, so time to move on. Can revisit later
     *
     * @return void
     */
    protected function runCategoryRules()
    {
        $this->fieldCategoryMap = $this->category->getFieldCategoriesToFieldsMap();

        // Don't generate field definitions if fields don't exist
        if ($this->category->getFields()->count() === 0 || $this->category->isLinkOnly()) {
            return;
        }

        /** VALIDATE ALL FIELD SLUGS FOR CATEGORY */
        $this->setRule('fields.*.slug', ['string', 'required', Rule::in($this->category->getFields()->getSlugsArray())]);

        /** ITEMS MUST BE ARRAY, CAN BE EMPTY */
        $this->setRule('fields.*.items', ['array']);

        /** OPTIONAL TEXT FIELDS */
        $this->setRule('fields.*.custom_title', ['string', 'nullable']);
        $this->setRule('fields.*.items.*.custom_heading', ['string', 'nullable']);
        $this->setRule('fields.*.items.*.additional_info', ['string', 'nullable']);

        foreach ($this->category->getFields() as $field) {
            $fieldKey = 'fields.' . $field->getSlug() . '.items.*.value';
            $ruleArray = [];

            /** REQUIRED OR AT LEAST ONE */
            if ($this->strict) {
                if ($field->isRequired()) {
                    $ruleArray[] = 'required';
                } elseif ($this->category->getFieldCategories()->isAtLeastOne($field->getCategory()) === true) {
                    $siblingFields = $this->fieldCategoryMap[$field->getCategory()];
                    array_forget($siblingFields, [array_search($field->getSlug(), $siblingFields)]);

                    $ruleArray[] = 'required_without_all:' . implode(',', $siblingFields);
                } else {
                    $ruleArray[] = 'nullable';
                }
            } else {
                $ruleArray[] = 'nullable';
            }

            /** MULTIPLE RULE */
            if ($field->canMultiple() !== true) {
                $this->setRule('fields.' . $field->getSlug() . '.items', ['array_size:1']);
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
                case 'list':
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

            $search = $search[$keyTree[$index]];

            $index++;
        }

        return $search;
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * Build the fully-qualified field key
     *
     * @param string $fieldKey
     * @return string
     */
    protected function getFullFieldKey(string $fieldKey)
    {
        if ($this->prefix) {
            return $this->prefix . '.' . $fieldKey;
        }

        return $fieldKey;
    }

    /**
     * Add new rules for a given key
     *
     * @param string $fieldKey
     * @param array $rule
     */
    protected function setRule(string $fieldKey, array $rule)
    {
        $newKey = $this->getFullFieldKey($fieldKey);

        if (isset($this->rules[$newKey]) && is_array($this->rules[$newKey]) === true) {
            $rule = array_merge($rule, $this->rules[$newKey]);
        }

        $this->rules[$newKey] = $rule;
    }

}