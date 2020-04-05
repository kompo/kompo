<?php

namespace Kompo\Tests\Feature\Model;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\KompoModel;

class _AssigningCreatedUpdatedByForm extends Form
{
	public $model = KompoModel::class;

	public function components()
	{
		return [
			Input::form('Name')
		];
	}

}