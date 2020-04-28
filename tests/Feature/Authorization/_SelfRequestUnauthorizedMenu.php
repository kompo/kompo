<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Menu;

class _SelfRequestUnauthorizedMenu extends Menu
{
	public function authorization()
	{
		return false;
	}
}