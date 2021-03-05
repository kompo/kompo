<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;
use Kompo\Tests\Models\Post;

class _QueryEloquentRelation extends Query
{
    use _EloquentFiltersTrait;

    public function query()
    {
        return Post::find(1)->objs(); //to return all of them
    }
}
