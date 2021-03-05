<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _SelectBelongsToForm extends Form
{
    use SwitchableFormTrait;

    public $model = Obj::class;

    public function komponents()
    {
        return $this->filter([
            Select::form('A')->name('belongsToPlain'),
            Select::form('A')->name('belongsToOrdered'),
            Select::form('A')->name('belongsToFiltered')->extraAttributes(['order' => 1]),
        ]);
    }
}
