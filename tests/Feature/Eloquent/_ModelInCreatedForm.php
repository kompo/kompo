<?php

namespace Kompo\Tests\Feature\Eloquent;

use Kompo\Form;
use Kompo\Tests\Models\Post;

class _ModelInCreatedForm extends Form
{
	public function created()
	{
		$post = Post::findOrNew($this->modelKey());

		$this->model($post);
	}
}