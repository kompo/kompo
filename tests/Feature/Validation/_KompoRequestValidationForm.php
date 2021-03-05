<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Form;
use Kompo\Input;

class _KompoRequestValidationForm extends Form
{
    public $submitTo = 'submit-route';

    public function komponents()
    {
        return [
            Input::form()->name('name'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
