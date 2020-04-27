<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;

class _SubmitUnauthorizedHandleForm extends Form
{
	public function authorize()
	{
		return false;
	}

	public function handle($request)
	{
		
	}
}