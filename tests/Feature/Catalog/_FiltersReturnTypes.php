<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;
use Kompo\Columns;

class _FiltersReturnTypes extends Catalog
{
	//Array
    public function top()
	{
		return [
			Columns::form(),
			null
		];
	}

	//Collection
    public function right()
	{
		return collect([
			Columns::form(),
			null
		]);
	}

	//One component
    public function bottom()
	{
		return Columns::form();
	}

	//null
	public function left()
	{
		return null;
	}
}