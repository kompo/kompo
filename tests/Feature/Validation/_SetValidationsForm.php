<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Form;
use Kompo\Input;

class _SetValidationsForm extends Form
{
    public function render()
    {
        return [
            Input::form('A')->name('name1'),
            Input::form('B')->name('name2')->rules(['bla', 'uuu']),
            Input::form('C')->name('name3')->rules('bue|effea'),
            Input::form('D')->name('name4')->rules(_UpperCaseRule::class),
            new _SetValidationsChildForm(),
        ];
    }

    public function rules()
    {
        return [
            'name1' => 'required|min:3',
            'name2' => 'weirdCharacters*-*23:2&de"fea',
            'name3' => ['unique', 'testing|insidearray'],
            'name4' => _UpperCaseRule::class,
        ];
    }
}
