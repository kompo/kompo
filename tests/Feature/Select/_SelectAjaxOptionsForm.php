<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Tag;

class _SelectAjaxOptionsForm extends Form
{
	public function komponents()
	{
		return [
			Select::form('tag')->searchOptions(1, 'anotherMethod'),
			MultiSelect::form('tags')->searchOptions(1),
			MultiSelect::form('tags_cast')->searchOptions(1)
		];
	}

	public function searchTags($search = '') //example with optional parameter
	{
		return Tag::where('name', 'LIKE', $search.'%')->pluck('name', 'id');		
	}

	public function anotherMethod() //example with no parameter
	{
		return $this->searchTags(request('search'));		
	}

	public function searchTags_cast($search) //example with mandatory parameter
	{
		return $this->searchTags($search);		
	}
}