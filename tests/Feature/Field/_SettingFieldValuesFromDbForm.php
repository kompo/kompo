<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Columns;
use Kompo\Date;
use Kompo\Form;
use Kompo\Input;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _SettingFieldValuesFromDbForm extends Form
{
    public $model = Post::class;

    public function render()
    {
        return [
            Columns::form(
                Input::form('Title'),
                Date::form('Published At')
            ),
            Select::form('Tags'),
            Input::form('Author')->name('author.name'),
        ];
    }
}
