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
        $query = _QueryEloquentModel::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_use_model_property()
    {
        factory(Obj::class, 10)->create();
        $query = _QueryEloquentModelProperty::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_eloquent_builder()
    {
        factory(Obj::class, 10)->create();
        $query = _QueryEloquentBuilder::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_eloquent_relation()
    {
        factory(Post::class, 1)->create();
        factory(Obj::class, 10)->create();
        $query = _QueryEloquentRelation::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_a_query_builder()
    {
        factory(Post::class, 10)->create();
        $query = _QueryDatabaseBuilder::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_a_collection()
    {
        $query = _QueryCollection::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array()
    {
        $query = _QueryArray::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_assoc_array()
    {
        $query = _QueryAssocArray::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array_of_objs()
    {
        $query = _QueryArrayOfObjs::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_an_array_of_arrays()
    {
        $query = _QueryArrayOfArrays::boot();

        $this->assertCount(10, $query->query->getCollection());
    }

    /** @test */
    public function query_returns_null()
    {
        $query = _QueryNull::boot();

        $this->assertCount(0, $query->query->getCollection());
    }
}
