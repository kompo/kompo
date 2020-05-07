<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Query;

class _BootUnauthorizedQuery extends Query
{
	public function authorizeBoot()
	{
		return false;
	}
}