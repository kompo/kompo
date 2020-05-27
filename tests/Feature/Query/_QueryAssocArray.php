<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryAssocArray extends Query
{
	use _CollectionFiltersTrait;

    public function query()
	{
		return collect($this->baseData)->mapWithKeys(function($val){
			return [$val => $val];
		});
	}
}