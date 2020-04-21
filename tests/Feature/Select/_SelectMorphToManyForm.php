<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;

class _SelectMorphToManyForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			MultiSelect::form('A')->name('morphToManyPlain'),
			MultiSelect::form('A')->name('morphToManyOrdered'),
			MultiSelect::form('A')->name('morphToManyFiltered')->extraAttributes(['order' => 1])
		];
	}
}