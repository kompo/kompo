<?php

namespace Kompo\Tests\Feature\Eloquent;

use Kompo\Form;
use Kompo\Tests\Models\Post;

class _PostForm extends Form
{
    public $model = Post::class;
}
