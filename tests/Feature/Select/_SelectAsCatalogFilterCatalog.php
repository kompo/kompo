<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Catalog;
use Kompo\Select;
use Kompo\Tests\Models\Obj;

class _SelectAsCatalogFilterCatalog extends Catalog
{	
    public function query()
	{
		return new Obj();
	}

	public function top()
	{
		return [
			Select::form()->name('belongsToPlain')
				->optionsFrom('id', 'name'),
			Select::form()->name('belongsToPlain.posts')
				->optionsFrom('id', 'title'),
			Select::form()->name('belongsToPlain.posts.tags')
				->optionsFrom('id', 'name')
		];
	}

}