<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\IconText;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Obj;

class _SelectOptionsFromForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			//From string
			Select::form('A')->name('belongsToPlain')->optionsFrom('id', 'name'),
			Select::form('A')->name('belongsToOrdered')->optionsFrom('id', 'name'),
			Select::form('A')->name('belongsToFiltered')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyPlain')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyOrdered')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyFiltered')->optionsFrom('id', 'name'),
			//Note: Morph relations on hold for Selects: see Obj Model for info

			//From Card
			Select::form('A')->name('belongsToOrdered')->optionsFrom('id', IconText::form([
				'text' => function($model){ 
					return strtoupper($model->name); 
				},
				'icon' => 'icon-location'
			])),

			//From Array
			Select::form('A')->name('belongsToOrdered')->optionsFrom('id', [
				'text' => function($model){ 
					return strtoupper($model->name); 
				},
				'icon' => 'icon-location'
			]),

			//From Closure
			Select::form('A')->name('belongsToOrdered')->optionsFrom('id', function($model){ 
				return strtoupper($model->name); 
			})
		];
	}
}