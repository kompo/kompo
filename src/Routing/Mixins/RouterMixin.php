<?php

namespace Kompo\Routing\Mixins;

use Kompo\Routing\Router;

class RouterMixin
{
    /*public function kompo()
    {
        return function ($uri, $komponentClass) {
            return Router::registerRoute($this, $uri, $komponentClass);
        };
    }*/

    public function layout()
    {
        return function ($layout) {
            return (new RouteRegistrarExtended($this))->layout($layout);
        };
    }

    public function section()
    {
        return function ($section) {
            return (new RouteRegistrarExtended($this))->section($section);
        };
    }
}
