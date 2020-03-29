<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;
use Kompo\Tests\Models\Obj;

class _QueryEloquentModelProperty extends Catalog
{
	use _EloquentFiltersTrait;

	public $model = Obj::class;
}