<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _ArrayFieldNameValidationForm extends Form
{
    public $model = Post::class;

    public function komponents()
    {
        return [
            Select::form()->name('tags'),
        ];
    }

    public function rules()
    {
        return [
            'tags.*.id' => 'required',
        ];
    }
}
