<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Menu;

class _RouteParametersMenu extends Menu
{
    use _RouteParametersCommonTrait;

    public function created()
    {
        $this->commonCreated();
    }
}
