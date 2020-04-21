<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _FormInstantiationForm extends Form
{
	public $model = Obj::class;

	public $modelId;

	public $store;

	public function created()
	{
		$this->modelId = $this->model->id;
		$this->store = $this->store();
	}
}