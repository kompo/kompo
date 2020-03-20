<?php

namespace Kompo\Tests\Feature\Form;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _MtmRelationshipInFieldNameForm extends Form
{
	public $model = Post::class;

	public function components()
	{
		return [
			Select::form('Tags')->name('tags.name'), //not OneToOne
		];
	}

}