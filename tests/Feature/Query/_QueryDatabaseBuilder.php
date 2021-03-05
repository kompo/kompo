<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryDatabaseBuilder extends Query
{
    public function query()
    {
        return \DB::table('posts')->where('id', '>=', 1); //to return all of them
    }
}
