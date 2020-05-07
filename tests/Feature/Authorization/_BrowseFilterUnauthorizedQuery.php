<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Query;

class _BrowseFilterUnauthorizedQuery extends Query
{
	public function authorize()
	{
		return false;
	}
}