<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Query;

class _SelfRequestUnauthorizedQuery extends Query
{
	public function authorization()
	{
		return false;
	}
}