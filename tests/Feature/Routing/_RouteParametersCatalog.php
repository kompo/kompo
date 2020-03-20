<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Catalog;

class _RouteParametersCatalog extends Catalog
{
	public $id = 'obj-id';

	public $store; //Had to expose it to see it in a route response
	public $parameters; //I had to expose the parameters to see them in a route response

	public function created()
	{
		$this->store = $this->store();
		$this->parameters = $this->parameter();
	}
}