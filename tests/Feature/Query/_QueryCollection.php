<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryCollection extends Query
{
    use _CollectionFiltersTrait;

    public function query()
    {
        return collect($this->baseData);
    }
}
