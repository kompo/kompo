<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;
use Kompo\Tests\Models\Obj;

class _QueryEloquentBuilder extends Query
{
    use _EloquentFiltersTrait;

    public function query()
    {
        return Obj::where('id', '>=', 1); //to return all of them
    }
}
