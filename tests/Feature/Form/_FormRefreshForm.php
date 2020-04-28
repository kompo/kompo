<?php

namespace Kompo\Tests\Feature\Form;

use Kompo\Form;
use Kompo\Tests\Models\Post;

class _FormRefreshForm extends Form
{
	protected $refresh = true;

	public $model = Post::class;
}