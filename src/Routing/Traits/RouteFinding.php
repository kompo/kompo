<?php

namespace Kompo\Routing\Traits;

use Kompo\Exceptions\EmptyRouteException;

trait RouteFinding
{
    /**
     * Guesses the desired route and method from the parameters.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @return mixed
     */
    public static function matchRoute($route, $parameters = null)
    {
        if(!$route)
            throw new EmptyRouteException();

        return is_null( static::getRouteByName($route) ) ? 
                    url($route, $parameters) : 
                    route($route, $parameters);
    }

    protected static function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }
}