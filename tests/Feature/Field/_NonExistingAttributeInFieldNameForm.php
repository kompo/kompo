<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _NonExistingAttributeInFieldNameForm extends Form
{
    public $model = Post::class;

    public function render()
    {
        return [
            Input::form('Label')->name('fneyaibyveiy'),
        ];
    }
}
