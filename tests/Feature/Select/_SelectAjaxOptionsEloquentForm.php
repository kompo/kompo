<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class _SelectAjaxOptionsEloquentForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			//attributes
			Select::form('tag')
				->searchOptions(1, 'searchTags'), //slow scenario no retrieveTag() method
			MultiSelect::form('tags')
				->searchOptions(1, 'searchTags', 'retrieveTags'),
			MultiSelect::form('tags_cast')
				->searchOptions(1, 'searchTags'),

			//relations without optionsFrom
			Select::form('A')->name('belongsToPlain')
				->searchOptions(1, 'searchUsers'),
			MultiSelect::form('A')->name('belongsToManyPlain')
				->searchOptions(1, 'searchFiles'),

			//relations with optionsFrom
			Select::form('A')->name('belongsToOrdered')
				->optionsFrom('id', 'name')
				->searchOptions(1, 'searchUsers'),
			MultiSelect::form('A')->name('belongsToManyOrdered')
				->optionsFrom('id', 'name')
				->searchOptions(1, 'searchFiles'),
			MultiSelect::form('A')->name('belongsToManyFiltered')
				->optionsFrom('id', 'name')
				->searchOptions(1, 'searchFiles')
		];
	}

	/********* SEARCH METHODS **************/

	public function searchTags() //Case 1: no parameter
	{
		return Tag::where('name', 'LIKE', request('search').'%')->pluck('name', 'id');		
	}

	public function searchUsers($search) //Case 2: mandatory parameter
	{
		return User::where('name', 'LIKE', $search.'%')->pluck('name', 'id');		
	}

	public function searchFiles($search = '') //Case 3: optional parameter
	{
		return File::where('name', 'LIKE', $search.'%')->pluck('name', 'id');		
	}

	/********* RETRIEVE METHODS **************/

	public function retrieveTags($value) //Parameter mandatory always
	{
		return Tag::where('id', $value)->pluck('name', 'id');		
	}

	public function retrieveTags_cast($value) //Parameter mandatory always
	{
		return Tag::where('id', $value)->pluck('name', 'id');		
	}

	public function retrieveFiles($value) //Case 3: optional parameter
	{
		return File::where('id', $value)->pluck('name', 'id');		
	}
}