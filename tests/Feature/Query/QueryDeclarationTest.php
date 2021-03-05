<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;

class QueryDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function query_returns_an_eloquent_model()
    {
        factory(Obj::class, 10)->create();
        $query = new _QueryEloquentModel();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_use_model_property()
    {
        factory(Obj::class, 10)->create();
        $query = new _QueryEloquentModelProperty();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_eloquent_builder()
    {
        factory(Obj::class, 10)->create();
        $query = new _QueryEloquentBuilder();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_eloquent_relation()
    {
        factory(Post::class, 1)->create();
        factory(Obj::class, 10)->create();
        $query = new _QueryEloquentRelation();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_a_query_builder()
    {
        factory(Post::class, 10)->create();
        $query = new _QueryDatabaseBuilder();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_a_collection()
    {
        $query = new _QueryCollection();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array()
    {
        $query = new _QueryArray();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_assoc_array()
    {
        $query = new _QueryAssocArray();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array_of_objs()
    {
        $query = new _QueryArrayOfObjs();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array_of_arrays()
    {
        $query = new _QueryArrayOfArrays();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_null()
    {
        $query = new _QueryNull();

        $this->assertCount(0, $query->query->getCollection());
    }
}
