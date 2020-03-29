<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;
use Kompo\Tests\Models\Obj;

class _QueryEloquentModel extends Catalog
{
	use _EloquentFiltersTrait;

    public function query()
	{
		return new Obj();
	}
}