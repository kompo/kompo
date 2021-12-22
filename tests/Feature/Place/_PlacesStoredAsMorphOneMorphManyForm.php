<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Form;
use Kompo\MultiPlace;
use Kompo\Place;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _PlacesStoredAsMorphOneMorphManyForm extends Form
{
    use SwitchableFormTrait;

    public $model = Obj::class;

    public function render()
    {
        return $this->filter([
            Place::form('A')->name('morphOnePlain2'),
            Place::form('A')->name('morphOneOrdered2'),
            Place::form('A')->name('morphOneFiltered2')->extraAttributes(['order' => 1]),
            MultiPlace::form('A')->name('morphManyPlain2'),
            MultiPlace::form('A')->name('morphManyOrdered2'),
            MultiPlace::form('A')->name('morphManyFiltered2')->extraAttributes(['order' => 1]),
        ]);
    }
}
