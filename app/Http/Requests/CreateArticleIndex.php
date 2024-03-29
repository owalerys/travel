<?php

namespace App\Http\Requests;

use App\ArticleVersion;
use App\Services\ValidationRulesService;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use libphonenumber\Leniency\Valid;

class CreateArticleIndex extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $version = ArticleVersion
            ::where('article_id', '=', $this->route()->parameter('article'))
            ->where('id', '=', $this->route()->parameter('version'))
            ->with('author')
            ->first();

        if (!$version || $this->user()->id !== $version->author->id) {
            return false;
        }

        return $this->user()->hasPermissionTo('create articles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $baseFields = [
            // 'airline_id' => ['required', 'integer', Rule::exists('airlines', 'id')->where(function (Builder $query) { $query->where('active', '=', 1); })],
            'content.title' => ['nullable', 'string', 'max:255'],
            'content.description' => ['nullable', 'string']
        ];

        /** @var ValidationRulesService $validationRulesService */
        $validationRulesService = resolve(ValidationRulesService::class);

        return array_merge($baseFields, $validationRulesService->getContentRules($this->input(), 'content', false));
    }
}
