<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Obj;

class _SelectOptionsFromForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			Select::form('A')->name('belongsToPlain')->optionsFrom('id', 'name'),
			Select::form('A')->name('belongsToOrdered')->optionsFrom('id', 'name'),
			Select::form('A')->name('belongsToFiltered')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyPlain')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyOrdered')->optionsFrom('id', 'name'),
			MultiSelect::form('A')->name('belongsToManyFiltered')->optionsFrom('id', 'name'),
			
			//Morphs on hold for Selects: see Obj Model for info
			//Select::form('A')->name('morphToPlain')->optionsFrom('id', 'name'),
			//Select::form('A')->name('morphToOrdered')->optionsFrom('id', 'name'),
			//Select::form('A')->name('morphToFiltered')->optionsFrom('id', 'name'),
			//MultiSelect::form('A')->name('morphToManyPlain')->optionsFrom('id', 'name'),
			//MultiSelect::form('A')->name('morphToManyOrdered')->optionsFrom('id', 'name'),
			//MultiSelect::form('A')->name('morphToManyFiltered')->optionsFrom('id', 'name'),
		];
	}
}