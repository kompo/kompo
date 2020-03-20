<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Place;
use Kompo\Form;
use Kompo\MultiPlace;
use Kompo\Tests\Models\Obj;

class _PlacesStoredAsMorphOneMorphManyForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			Place::form('A')->name('morphOnePlain2'),
			Place::form('A')->name('morphOneOrdered2'),
			Place::form('A')->name('morphOneFiltered2')->extraAttributes(['order' => 1]),
			MultiPlace::form('A')->name('morphManyPlain2'),
			MultiPlace::form('A')->name('morphManyOrdered2'),
			MultiPlace::form('A')->name('morphManyFiltered2')->extraAttributes(['order' => 1])
		];
	}
}