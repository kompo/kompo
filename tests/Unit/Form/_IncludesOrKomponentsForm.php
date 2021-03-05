<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Input;

class _IncludesOrKomponentsForm extends Form
{
    public function komponents()
    {
        return [
            Input::form('Title')->getKomponents('newKompos'),
        ];
    }

    public function newkompos()
    {
        return [
            Input::form('Content'),
        ];
    }
}
