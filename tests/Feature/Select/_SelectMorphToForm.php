<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\User;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _SelectMorphToForm extends Form
{
	use SwitchableFormTrait;

	public $model = Obj::class;

	public function komponents()
	{
		return $this->filter([
			Select::form('A')->name('morphToPlain')
				->optionsFrom('id', 'name', User::class), //MorphTo model can either be specified with the 3rd param of optionsFrom().
			Select::form('A')->name('morphToOrdered')
				->morphToModel(User::class),              //MorphTo model can either be specified with morphToModel().
			Select::form('A')->name('morphToFiltered')
				->morphToModel(User::class)->extraAttributes(['order' => 1])
		]);
	}
}