<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryArray extends Query
{
	use _CollectionFiltersTrait;

    public function query()
	{
		return $this->baseData;
	}
}