<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Menu;

class _BootUnauthorizedMenu extends Menu
{
	public function authorizeBoot()
	{
		return false;
	}
}