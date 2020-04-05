<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _FieldNameOneToOneParsingForm extends Form
{
	public $model = Post::class;

	public function components()
	{
		return [
			Input::form('Post Title')->name('title'),
			Input::form('Has One Obj Title')->name('obj.title'),
			Input::form('Has One Obj Tag')->name('obj.tag'),
			Input::form('Has One 2 Title')->name('postTag.title')
		];
	}

}