<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Tests\Models\Post;

class _NullableButNonNullValuesForm extends Form
{
	public $model = Post::class;

	public function komponents()
	{
		return [
			_Input('Title')->name('title'),
			_Input('Integer')->name('integer'),
		];
	}

}