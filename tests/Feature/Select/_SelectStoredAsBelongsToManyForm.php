<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Select;
use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;

class _SelectStoredAsBelongsToManyForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			Select::form('A')->name('belongsToPlain'),
			Select::form('A')->name('belongsToOrdered'),
			Select::form('A')->name('belongsToFiltered')->extraAttributes(['order' => 1]),
			MultiSelect::form('A')->name('belongsToManyPlain'),
			MultiSelect::form('A')->name('belongsToManyOrdered'),
			MultiSelect::form('A')->name('belongsToManyFiltered')->extraAttributes(['order' => 1])
		];
	}
}