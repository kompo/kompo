<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Post;

class DateRangeTest extends EnvironmentBoot
{
	/** @test */
	public function custom_set_value_works_for_date_range()
	{
		$post = factory(Post::class, 1)->create()->first();

		$form = new _DateRangeForm(1);

		$this->assertIsArray($form->components[0]->value);
		$this->assertEquals($post->created_at, $form->components[0]->value[0]);
		$this->assertEquals($post->updated_at, $form->components[0]->value[1]);
	}

}