<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;

class _SelectMorphedByManyForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			MultiSelect::form('A')->name('morphedByManyPlain'),
			MultiSelect::form('A')->name('morphedByManyOrdered'),
			MultiSelect::form('A')->name('morphedByManyFiltered')->extraAttributes(['order' => 1])
		];
	}
}