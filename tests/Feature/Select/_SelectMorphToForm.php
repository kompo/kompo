<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\User;

class _SelectMorphToForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			Select::form('A')->name('morphToPlain')
				->optionsFrom('id', 'name', User::class), //MorphTo model can either be specified with the 3rd param of optionsFrom().
			Select::form('A')->name('morphToOrdered')
				->morphToModel(User::class),              //MorphTo model can either be specified with morphToModel().
			Select::form('A')->name('morphToFiltered')
				->morphToModel(User::class)->extraAttributes(['order' => 1])
		];
	}
}