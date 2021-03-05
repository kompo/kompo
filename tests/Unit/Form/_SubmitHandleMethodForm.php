<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _SubmitHandleMethodForm extends Form
{
    public $model = Obj::class; //only to make sure that handle() takes precedence

    public function handle()
    {
        return 'test custom handle string';
    }
}
