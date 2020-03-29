<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;
use Kompo\Tests\Models\Obj;

class _QueryEloquentBuilder extends Catalog
{
	use _EloquentFiltersTrait;
	
    public function query()
	{
		return Obj::where('id', '>=', 1); //to return all of them
	}
}