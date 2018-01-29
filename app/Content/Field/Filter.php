<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 29/01/18
 * Time: 12:31 PM
 */

namespace App\Content\Field;


use App\Content\Field\Filter\Email;
use App\Content\Field\Filter\Free;
use App\Content\Field\Filter\Paragraph;
use App\Content\Field\Filter\Phone;
use App\Content\Field\Filter\StringFilter;
use App\Content\Field\Filter\TicketDesignator;
use App\Content\Field\Filter\TourCode;
use App\Content\Field\Filter\Url;

abstract class Filter
{
    private $content;

    private $errors = [];

    private $failedValidation = false;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function validate()
    {
        $this->preValidate();

        $this->runValidation($this->retrieve());

        $this->postValidate();

        return $this->failedValidation;
    }

    protected function preValidate()
    {
        $this->resetErrors();
    }

    protected function postValidate()
    {

    }

    protected function retrieve()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return bool
     */
    abstract protected function runValidation($content);

    protected function setError(string $error)
    {
        $this->failedValidation = true;

        $this->errors[] = $error;
    }

    private function resetErrors()
    {
        $this->failedValidation = false;

        $this->errors = [];
    }

    /**
     * @param $content
     * @param $filterType
     * @return Filter
     * @throws \Exception
     */
    public static function getFilter($content, string $filterType)
    {
        switch ($filterType) {
            case 'email':
                return new Email($content);
            case 'free':
                return new Free($content);
            case 'paragraph':
                return new Paragraph($content);
            case 'phone':
                return new Phone($content);
            case 'string':
                return new StringFilter($content);
            case 'ticket-designator':
                return new TicketDesignator($content);
            case 'tour-code':
                return new TourCode($content);
            case 'url':
                return new Url($content);
            default:
                throw new \Exception('Undefined filter type ' . $filterType);
        }
    }
}