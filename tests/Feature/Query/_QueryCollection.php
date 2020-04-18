<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryCollection extends Query
{
    public function query()
	{
		return collect([1,2,3,4,5,6,7,8,9,10]);
	}
}