<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Place;
use Kompo\Form;
use Kompo\MultiPlace;
use Kompo\Tests\Models\Obj;

class _PlacesStoredAsSingleColumnForm extends Form
{
	public $model = Obj::class;

	public function komponents()
	{
		return [
			Place::form('A')->name('place'),
			Place::form('A')->name('place_cast'),
			MultiPlace::form('A')->name('places'),
			MultiPlace::form('A')->name('places_cast')
		];
	}
}