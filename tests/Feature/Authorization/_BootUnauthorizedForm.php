<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;

class _BootUnauthorizedForm extends Form
{
	public function authorizeBoot()
	{
		return false;
	}
}