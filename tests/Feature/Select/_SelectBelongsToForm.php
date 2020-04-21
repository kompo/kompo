<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Select;
use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _SelectBelongsToForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			Select::form('A')->name('belongsToPlain'),
			Select::form('A')->name('belongsToOrdered'),
			Select::form('A')->name('belongsToFiltered')->extraAttributes(['order' => 1])
		];
	}
}