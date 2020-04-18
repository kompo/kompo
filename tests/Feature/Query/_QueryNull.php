<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryNull extends Query
{
    public function query()
	{
		return null;
	}
}