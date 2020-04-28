<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;

class _SelfRequestUnauthorizedForm extends Form
{
	public function authorization()
	{
		return false;
	}
}