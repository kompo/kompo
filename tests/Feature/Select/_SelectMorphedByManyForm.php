<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _SelectMorphedByManyForm extends Form
{
    use SwitchableFormTrait;

    public $model = Obj::class;

    public function render()
    {
        return $this->filter([
            MultiSelect::form('A')->name('morphedByManyPlain'),
            MultiSelect::form('A')->name('morphedByManyOrdered'),
            MultiSelect::form('A')->name('morphedByManyFiltered')->extraAttributes(['order' => 1]),
        ]);
    }
}
