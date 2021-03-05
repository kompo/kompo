<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _NestedFieldNameValidationForm extends Form
{
    public $model = Post::class;

    public function komponents()
    {
        return [
            Input::form('Has One Obj Title')->name('obj.title'),
            Input::form('Has One Obj Tag')->name('obj.tag'),
            Input::form('Has One 2 Title')->name('postTag.title'),
        ];
    }

    public function rules()
    {
        return [
            'obj.title'     => 'required',
            'obj_tag'       => 'required',  //simulating developer mistake
            'postTag.title' => 'required',
        ];
    }
}
