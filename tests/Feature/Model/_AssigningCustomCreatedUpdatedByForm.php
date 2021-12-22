<?php

namespace Kompo\Tests\Feature\Model;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\KompoModelCustom;

class _AssigningCustomCreatedUpdatedByForm extends Form
{
    public $model = KompoModelCustom::class;

    public function render()
    {
        return [
            Input::form('Name'),
        ];
    }
}
