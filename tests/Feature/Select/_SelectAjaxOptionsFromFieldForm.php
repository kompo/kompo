<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class _SelectAjaxOptionsFromFieldForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			//attributes
			Select::form('tag')
				->optionsFromField('somename', 'anotherMethod'),
			MultiSelect::form('tags')
				->optionsFromField('somename'), //no Method specified
			MultiSelect::form('tags_cast')
				->searchOptions('somename', 'searchTags'),

			//relations without optionsFrom
			Select::form('A')->name('belongsToPlain')
				->optionsFromField('somename', 'getUsers'),
			MultiSelect::form('A')->name('belongsToManyPlain')
				->optionsFromField('somename', 'searchFiles'),

			//relations with optionsFrom
			Select::form('A')->name('belongsToOrdered')
				->optionsFrom('id', 'name')
				->optionsFromField('somename', 'getUsers'),
			MultiSelect::form('A')->name('belongsToManyOrdered')
				->optionsFrom('id', 'name')
				->optionsFromField('somename', 'searchFiles'),
			MultiSelect::form('A')->name('belongsToManyFiltered')
				->optionsFrom('id', 'name')
				->optionsFromField('somename', 'searchFiles')
		];
	}

	/********* SEARCH METHODS **************/

	public function anotherMethod()
	{
		return $this->searchTags(request('search'));		
	}

	public function searchTags($value)
	{
		return Tag::where('category_id', $value)->pluck('name', 'id');		
	}

	public function getUsers($value)
	{
		return User::where('order', $value)->pluck('name', 'id');		
	}

	public function searchFiles($value)
	{
		return File::where('mime_type', $value)->pluck('name', 'id');		
	}
}