<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _SubmitUnauthorizedEloquentForm extends Form
{
    public $model = Obj::class;

    public function authorize()
    {
        return false;
    }
}
