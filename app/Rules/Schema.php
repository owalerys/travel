<?php

namespace App\Rules;

use App\Repositories\SchemaRepository;
use Illuminate\Contracts\Validation\Rule;

class Schema implements Rule
{

    /** @var SchemaRepository */
    protected $schemaRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(SchemaRepository $schemaRepository)
    {
        $this->schemaRepository = $schemaRepository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value)
    {
        $value = (string) $value;

        $exploded = explode(',', $value);

        $category = $this->schemaRepository->getCategoryBySlug($exploded[0], $exploded[1]);

        if ($category === false) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute schema identifier is invalid. (version, category_slug)';
    }
}
