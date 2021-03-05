<?php

namespace Kompo\Routing\Mixins;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

trait ExtendsRoutingTrait
{
    protected function extendRouting()
    {
        Route::mixin(new RouteMixin());
        Router::mixin(new RouterMixin());
    }
}
