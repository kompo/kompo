<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;

class _QueryNull extends Catalog
{
    public function query()
	{
		return null;
	}
}