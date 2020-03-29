<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Catalog;

class _QueryDatabaseBuilder extends Catalog
{
    public function query()
	{
		return \DB::table('posts')->where('id', '>=', 1); //to return all of them
	}
}