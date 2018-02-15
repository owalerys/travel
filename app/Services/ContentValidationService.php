<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 09/02/18
 * Time: 10:54 AM
 */

namespace App\Services;


use Illuminate\Support\MessageBag;

class ContentValidationService
{

    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var MessageBag
     */
    protected $messageBag;

    /**
     * @var bool
     */
    protected $strictValidation = true;

    /**
     * @var bool
     */
    protected $failed = false;

    /**
     * @var string
     */
    protected $fieldPrefix = 'content';

    /**
     * @var FieldValidationService
     */
    protected $fieldValidationService;

    public function __construct(FieldValidationService $fieldValidationService)
    {
        $this->fieldValidationService = $fieldValidationService;
    }

    /**
     * @param array $content
     */
    protected function validateContent(array $content, bool $strict)
    {
        $this->content = $content;

        $this->strictValidation = $strict;

        $this->reset();

        $this->validate();

        return $this->hasFailure();
    }

    public function setFieldPrefix(string $prefix = null)
    {
        $this->fieldPrefix = $prefix;
    }

    /**
     * @param array $content
     * @return bool
     */
    public function hardValidation(array $content)
    {
        return $this->validateContent($content, true);
    }

    /**
     * @param array $content
     * @return bool
     */
    public function softValidation(array $content)
    {
        return $this->validateContent($content, false);
    }

    /**
     *
     */
    protected function validate()
    {
        //iterate over fields
        //set errors
        //send messages to message bag
    }

    /**
     * @return bool
     */
    protected function hasFailure()
    {
        return $this->failed;
    }

    /**
     *
     */
    protected function reset()
    {
        $this->initializeMessageBag();

        $this->failed = false;
    }

    protected function initializeMessageBag()
    {
        $this->messageBag = new MessageBag();

        return $this->messageBag;
    }

    /**
     * @param string $fieldSlug
     * @param int $fieldIndex
     * @param string $error
     */
    protected function pushError(string $fieldSlug, int $fieldIndex, string $error)
    {
        if ($this->fieldPrefix) {
            $this->messageBag->add( $this->fieldPrefix . '.fields.' . $fieldSlug . '.' . $fieldIndex, $error);
        } else {
            $this->messageBag->add('fields.' . $fieldSlug . '.' . $fieldIndex, $error);
        }
    }

    /**
     * @return MessageBag
     */
    public function getMessageBag()
    {
        return $this->messageBag ?: $this->initializeMessageBag();
    }

}