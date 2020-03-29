<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _SubmitEloquentSaveForm extends Form
{
	public $model = Obj::class;
}