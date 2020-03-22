<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _RouteParametersForm extends Form
{
	public $id = 'obj-id';

	protected $metaTags = [
		'title' => 'meta-title',
		'description' => 'meta description',
		'keywords' => 'key,word'
	];

	public $model = Post::class; //otherwise modelKey is not set

	public $modelKey; //Had to expose it to see it in a route response
	public $store; //Had to expose it to see it in a route response
	public $parameters; //Had to expose it to see them in a route response

	public function created()
	{
		$this->modelKey = $this->modelKey();
		$this->store = $this->store();
		$this->parameters = $this->parameter();
	}

	public function components()
	{
		return Input::form('Form-is-rendered');
	}
}