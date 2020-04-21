<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Place;
use Kompo\Form;
use Kompo\MultiPlace;
use Kompo\Tests\Models\Obj;

class _PlacesStoredAsHasOneHasManyForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			Place::form('A')->name('hasOnePlain2'),
			Place::form('A')->name('hasOneOrdered2'),
			Place::form('A')->name('hasOneFiltered2')->extraAttributes(['order' => 1]),
			MultiPlace::form('A')->name('hasManyPlain2'),
			MultiPlace::form('A')->name('hasManyOrdered2'),
			MultiPlace::form('A')->name('hasManyFiltered2')->extraAttributes(['order' => 1])
		];
	}
}