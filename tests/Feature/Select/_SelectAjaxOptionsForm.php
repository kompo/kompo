<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _SelectAjaxOptionsForm extends Form
{
	public $model = Post::class;

	public function components()
	{
		return [
			Select::form('tag')->searchOptions(1),
			MultiSelect::form('tags')->searchOptions(1)
		];
	}

	public function searchOptions($search, $searchByValue = false)
	{
		
	}
}