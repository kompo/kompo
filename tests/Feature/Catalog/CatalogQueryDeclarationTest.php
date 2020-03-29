<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;

class CatalogQueryDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function query_returns_an_eloquent_model()
	{
		factory(Obj::class, 10)->create();
		$catalog = new _QueryEloquentModel();

		$this->assertCount(10, $catalog->query->getCollection());
	}

	/** @test */
	public function query_use_model_property()
	{
		factory(Obj::class, 10)->create();
		$catalog = new _QueryEloquentModelProperty();

		$this->assertCount(10, $catalog->query->getCollection());
	}

	/** @test */
	public function query_returns_an_eloquent_builder()
	{
		factory(Obj::class, 10)->create();
		$catalog = new _QueryEloquentBuilder();

		$this->assertCount(10, $catalog->query->getCollection());
	}

	/** @test */
	public function query_returns_an_eloquent_relation()
	{
		factory(Post::class, 1)->create();
		factory(Obj::class, 10)->create();
		$catalog = new _QueryEloquentRelation();

		$this->assertCount(10, $catalog->query->getCollection());
	}


	/** @test */
	public function query_returns_a_query_builder()
	{
		factory(Post::class, 10)->create();
		$catalog = new _QueryDatabaseBuilder();

		$this->assertCount(10, $catalog->query->getCollection());
	}


	/** @test */
	public function query_returns_a_collection()
	{
		$catalog = new _QueryCollection();

		$this->assertCount(10, $catalog->query->getCollection());
	}


	/** @test */
	public function query_returns_an_array()
	{
		$catalog = new _QueryArray();

		$this->assertCount(10, $catalog->query->getCollection());
	}

	/** @test */
	public function query_returns_null()
	{
		$catalog = new _QueryNull();

		$this->assertCount(0, $catalog->query->getCollection());
	}


}