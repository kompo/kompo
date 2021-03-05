<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Form;
use Kompo\Place;
use Kompo\Tests\Models\Place as PlaceModel;

class _PlaceAttributesToColumnsForm extends Form
{
    public $model = PlaceModel::class;

    public function komponents()
    {
        return [
            //When AttributesToColumns, the name of the field should correspond to the path column

            $this->store('withExtraAttributes') ?

                Place::form('Place')->name('address')->attributesToColumns()->extraAttributes([
                    'all_columns' => 'some-constant',
                ]) :

                Place::form('Place')->name('address')->attributesToColumns(),

            //no MultiPlace - only for single Place
        ];
    }
}
