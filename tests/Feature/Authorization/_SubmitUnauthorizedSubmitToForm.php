<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;

class _SubmitUnauthorizedSubmitToForm extends Form
{
	public $submitTo = 'submit-route';
	
	public function authorization() //used in FormRequest
	{
		return false;
	}
}