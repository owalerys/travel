<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 14/02/18
 * Time: 4:37 PM
 */

namespace App\Content;


use App\Services\ContentValidationService;

class Validator
{

    /**
     * @var ContentValidationService
     */
    protected $contentValidationService;

    public function __construct(ContentValidationService $contentValidationService)
    {
        $this->contentValidationService = $contentValidationService;
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $parameters
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Exception
     */
    public function content($attribute, $value, $parameters, $validator)
    {
        if (in_array($parameters[0], ['hard', 'soft']) === false) {
            throw new \Exception('Content validation attribute required, not valid: \'' . $attribute . '\'');
        }

        $strictValidation = true;
        if ($parameters[0] === 'soft') {
            $strictValidation = false;
        }

        $this->contentValidationService->setFieldPrefix($attribute);

        if ($strictValidation) {
            $result = $this->contentValidationService->hardValidation($value);
        } else {
            $result = $this->contentValidationService->softValidation($value);
        }

        $extendedMessageBag = $this->contentValidationService->getMessageBag();

        $validator->after(function ($validator) use ($result, $extendedMessageBag) {
            if ($result) {
                /** @var $validator \Illuminate\Contracts\Validation\Validator */
                $validator->getMessageBag()->merge($extendedMessageBag);
            }
        });

        return $result;
    }

}