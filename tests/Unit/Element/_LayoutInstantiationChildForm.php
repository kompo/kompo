<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Columns;
use Kompo\Form;
use Kompo\Input;

class _LayoutInstantiationChildForm extends Form
{
    public function render()
    {
        return [
            Columns::form(
                Input::form()->id('B'),
                null,
                Input::form()->id('C')
            ),
        ];
    }
}
