<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Menu;

class _SelfRequestUnauthorizedMenu extends Menu
{
	public function authorize()
	{
		return false;
	}
}