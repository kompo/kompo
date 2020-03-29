<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\DateRange;
use Kompo\Input;
use Kompo\MultiSelect;
use Kompo\Select;

trait _EloquentFiltersTrait
{
	public function top()
	{
		return [
			//Attributes
			Input::form('Title')->filterBy(), //defaults to LIKE
			Input::form('A')->filterBy('equal', '='), //overwrite Input LIKE default
			Input::form('A')->filterBy('greater', '>='),
			Input::form('A')->filterBy('lower', '<='),
			Input::form('A')->filterBy('like', 'LIKE'),
			Input::form('A')->filterBy('startswith', 'STARTSWITH'),
			Input::form('A')->filterBy('endswith', 'ENDSWITH'),
			DateRange::form('A')->filterBy('between', 'BETWEEN'),
			MultiSelect::form('A')->filterBy('in', 'IN'),
		];
	}

	public function bottom()
	{
		return [
			//Relations
			Select::form('A')->filterBy('belongsToPlain'),
			Select::form('A')->filterBy('belongsToPlain.name', '>='),
			Select::form('A')->filterBy('belongsToOrdered.name', 'ENDSWITH'),
			Select::form('A')->filterBy('belongsToFiltered.name'),
			Select::form('A')->filterBy('belongsToPlain.posts.title', 'LIKE'),
		];
	}

	public function card($item)
	{
		return [
			'user_id' => $item->user_id
		];
	}
	
}