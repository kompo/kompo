<?php

namespace Kompo\Routing;

use Kompo\Exceptions\EmptyRouteException;

class RouteFinder
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

    /**
     * Adds the main kompo route point to the komponent.
     *
     * @param mixed $kompo  A kompo object
     *
     * @return self
     */
    public static function activateRoute($kompo)
    {
        return $kompo->data([
            'kompoRoute' => route('_kompo')
        ]);
    }


    /**
     * Returns thr current route's parameters
     *
     * @return     <type>  The route parameters.
     */
    public static function getRouteParameters()
    {
        return request()->route() ? request()->route()->parameters() : [];
        //Other options:
        
        //Option: 1
        //$request->route()->parametersWithoutNulls()
        
        //Option: 2
        //$names = $request->route()->parameterNames();
        //return collect($request->route()->parameters())->filter(function($param, $key) use ($names){
        //    return in_array($key, $names);
        //})->all();
    }

    protected static function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }
}