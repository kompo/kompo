<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;
use Kompo\Tests\Models\Obj;

class _QueryEloquentModelProperty extends Query
{
	use _EloquentFiltersTrait;

	public $model = Obj::class;
}