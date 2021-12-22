<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Input;

class _IncludesOrElementsForm extends Form
{
    public function render()
    {
        return [
            Input::form('Title')->getElements('newKompos'),
        ];
    }

    public function newkompos()
    {
        return [
            Input::form('Content'),
        ];
    }
}
