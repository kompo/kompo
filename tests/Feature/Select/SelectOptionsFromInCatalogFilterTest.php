<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Post;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class SelectOptionsFromInCatalogFilterTest extends EnvironmentBoot
{
	/** @test */
	public function options_are_infered_in_catalog_filters()
	{	
		$users = factory(User::class, 6)->create(); //because +1 auth user
		$posts = factory(Post::class, 8)->create();
		$tags = factory(Tag::class, 9)->create();

		$catalog = new _SelectAsCatalogFilterCatalog();

		$this->assertCount(7, $catalog->filters['top'][0]->options);
		$this->assertCount(8, $catalog->filters['top'][1]->options);
		$this->assertCount(9, $catalog->filters['top'][2]->options);
	}
}