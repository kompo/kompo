<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;

class _SelectBelongsToManyForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			MultiSelect::form('A')->name('belongsToManyPlain'),
			MultiSelect::form('A')->name('belongsToManyOrdered'),
			MultiSelect::form('A')->name('belongsToManyFiltered')->extraAttributes(['order' => 1])
		];
	}
}