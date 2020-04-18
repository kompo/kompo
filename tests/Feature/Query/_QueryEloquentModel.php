<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;
use Kompo\Tests\Models\Obj;

class _QueryEloquentModel extends Query
{
	use _EloquentFiltersTrait;

    public function query()
	{
		return new Obj();
	}
}