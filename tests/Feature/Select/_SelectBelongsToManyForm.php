<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _SelectBelongsToManyForm extends Form
{
	use SwitchableFormTrait;

	public $model = Obj::class;

	public function komponents()
	{
		return $this->filter([
			MultiSelect::form('A')->name('belongsToManyPlain'),
			MultiSelect::form('A')->name('belongsToManyOrdered'),
			MultiSelect::form('A')->name('belongsToManyFiltered')->extraAttributes(['order' => 1])
		]);
	}
}