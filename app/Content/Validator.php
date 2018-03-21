<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 14/02/18
 * Time: 4:37 PM
 */

namespace App\Content;

class Validator
{

    /**
     * @param $attribute string
     * @param $parameters array
     * @param $value mixed
     * @param $validator \Illuminate\Contracts\Validation\Validator
     * @return bool
     */
    public function ruleArraySize($attribute, $parameters, $value, $validator)
    {
        if (is_array($value) === false) {
            return true;
        } else {
            return count($value) <= $parameters[0];
        }
    }

    /**
     * @param $message string
     * @param $attribute string
     * @param $rule string
     * @param $parameters array
     * @return string
     */
    public function replacerArraySize($message, $attribute, $rule, $parameters)
    {
        return str_replace(':array_size', $parameters[0], $message);
    }

}