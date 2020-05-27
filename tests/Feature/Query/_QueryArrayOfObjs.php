<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;
use Kompo\Tests\Models\Obj;

class _QueryArrayOfObjs extends Query
{
	use _CollectionFiltersTrait;

    public function query()
	{
		return collect($this->baseData)->map(function($val){
			$obj = new \stdClass();
			$obj->input = $val;
			$obj->select = $val;
			$obj->multiselect = $val;
			$obj->equal = $val;
			$obj->greater = $val;
			$obj->lower = $val;
			$obj->like = $val;
			$obj->startswith = $val;
			$obj->endswith = $val;
			$obj->between = $val;
			$obj->in = $val;
			return $obj;
		});
	}
}