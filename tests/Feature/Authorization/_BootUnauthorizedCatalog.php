<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Catalog;

class _BootUnauthorizedCatalog extends Catalog
{
	public function bootAuthorization()
	{
		return false;
	}
}