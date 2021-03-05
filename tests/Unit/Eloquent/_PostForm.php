<?php

namespace Kompo\Tests\Unit\Eloquent;

use Kompo\Form;
use Kompo\Tests\Models\Post;

class _PostForm extends Form
{
    public $model = Post::class;
}
