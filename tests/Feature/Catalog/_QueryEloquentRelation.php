<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;
use Kompo\Tests\Models\Post;

class _QueryEloquentRelation extends Catalog
{
	use _EloquentFiltersTrait;

    public function query()
	{
		return Post::find(1)->objs(); //to return all of them
	}
}