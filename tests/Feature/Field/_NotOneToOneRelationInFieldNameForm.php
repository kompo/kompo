<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _NotOneToOneRelationInFieldNameForm extends Form
{
	public $model = Post::class;

	public function komponents()
	{
		return [
			Select::form('Tags')->name('tags.name'), //not OneToOne
		];
	}

}