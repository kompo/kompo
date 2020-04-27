<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Query;
use Kompo\Select;
use Kompo\Tests\Models\Post;

class _NestedFieldNameValidationQuery extends Query
{
	public function query()
	{
		return new Post();
	}

	public function top()
	{
		return [
			Select::form('Has One Obj Title')->name('obj.title'),
			Select::form('Has One Obj Tag')->name('obj.tag'),
			Select::form('Has One 2 Title')->name('postTag.title')
		];
	}

	public function rules()
	{
		return [
			'obj.title' => 'required',
			'obj`tag' => 'required', //simulating developer mistake
			'postTag.title' => 'required',
		];
	}

}