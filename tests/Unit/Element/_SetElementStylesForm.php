<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Form;
use Kompo\Input;

class _SetElementStylesForm extends Form
{
    public function komponents()
    {
        return [
            Input::form('Title')->style('margin:0'),
            Input::form('Title')->style('anything')->style('margin:0'),
            Input::form('Title')->addStyle('margin:0'),
            Input::form('Title')->style('margin:0')->addStyle('padding:0'),
            Input::form('Title')->style('margin:0')->addStyle('padding:0;color:red'),
        ];
    }
}
