<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Columns;
use Kompo\Query;

class _FiltersReturnTypes extends Query
{
    //Array
    public function top()
    {
        return [
            Columns::form(),
            null,
        ];
    }

    //Collection
    public function right()
    {
        return collect([
            Columns::form(),
            null,
        ]);
    }

    //One component
    public function bottom()
    {
        return Columns::form();
    }

    //null
    public function left()
    {
        return null;
    }
}
