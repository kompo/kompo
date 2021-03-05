<?php

namespace Kompo\Routing\Mixins;

use Kompo\Routing\Router;

class RouterMixin
{
    /*public function kompo()
    {
        return function ($uri, $komposerClass) {
            return Router::registerRoute($this, $uri, $komposerClass);
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
