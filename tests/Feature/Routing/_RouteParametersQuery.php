<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Query;

class _RouteParametersQuery extends Query
{
    use _RouteParametersCommonTrait;

    public function created()
    {
        $this->commonCreated();
    }
}
