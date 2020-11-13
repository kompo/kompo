<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _SelectMorphToManyForm extends Form
{
	use SwitchableFormTrait;

	public $model = Obj::class;

	public function komponents()
	{
		return $this->filter([
			MultiSelect::form('A')->name('morphToManyPlain'),
			MultiSelect::form('A')->name('morphToManyOrdered'),
			MultiSelect::form('A')->name('morphToManyFiltered')->extraAttributes(['order' => 1])
		]);
	}
}