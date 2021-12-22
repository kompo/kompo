<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Form;
use Kompo\Input;

class _SetElementIdForm extends Form
{
    public $id = 'form-id';

    public function render()
    {
        return [
            Input::form('Title'),
            Input::form('Title')->id('some-id'),
            Input::form('Title')->id('some-id')->id('override-id'),
        ];
    }
}
