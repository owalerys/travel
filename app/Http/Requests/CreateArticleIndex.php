<?php

namespace App\Http\Requests;

use App\Services\ValidationRulesService;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateArticleIndex extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermissionTo('create articles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(ValidationRulesService $validationRulesService)
    {
        $baseFields = [
            'airline_id' => ['required', 'integer', Rule::exists('airlines', 'id')->where(function (Builder $query) { $query->where('active', '=', 1); })],
            'content.title' => ['nullable', 'string', 'max:255', 'required'],
            'content.description' => ['nullable', 'string']
        ];

        return array_merge($baseFields, $validationRulesService->getContentRules($this->input(), 'content', false));
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {

    }
}
