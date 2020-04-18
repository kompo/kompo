<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _FieldOutputValueForm extends Form
{
	public $model = Post::class;

	public function components()
	{
		return [
			Input::form()->name('title')->value('post-title'),
			Input::form()->name('obj.title')->value('obj-title'),
			Input::form()->name('content')->default('default-content'),
			Input::form()->name('obj.tag')->default('default-tag')
		];
	}

}