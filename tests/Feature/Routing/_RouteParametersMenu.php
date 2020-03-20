<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Menu;

class _RouteParametersMenu extends Menu
{
	public $id = 'obj-id';

	public $data = ['kompoId' => 'kompoId']; //To inspect session data

	public $store; //Had to expose it to see it in a route response
	public $parameters; //I had to expose the parameters to see them in a route response

	public function created()
	{
		$this->store = $this->store();
		$this->parameters = $this->parameter();
	}
}