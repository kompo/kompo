<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _NonExistingAttributeInFieldNameForm extends Form
{
    public $model = Post::class;

    public function komponents()
    {
        return [
            Input::form('Label')->name('fneyaibyveiy'),
        ];
    }
}
