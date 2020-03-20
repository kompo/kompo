<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Contracts\Validation\Rule;

class _UppercaseRule implements Rule
{
    public function passes($attribute, $value)
    {
        return strtoupper($value) === $value;
    }

    public function message()
    {
        return 'The :attribute must be uppercase.';
    }
}