<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _ModelHidingForm extends Form
{
	public $model = Obj::class;

	public function showModel()
	{
		$this->hideModel = false;
	}

	public function hideModel()
	{
		$this->hideModel = true;
	}
}