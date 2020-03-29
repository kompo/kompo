<?php

namespace Kompo\Routing;

use Kompo\Exceptions\EmptyRouteException;

class RouteFinder
{
    /**
     * Gets the default route that handles all Kompo requests.
     *
     * @return string.
     */
    public static function getKompoRoute()
    {
        return route('_kompo');
    }

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
            'kompoRoute' => static::getKompoRoute()
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

    /**
     * Guesses the desired route and method from the parameters.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @return mixed
     */
    public static function guessRoute($route, $parameters = null)
    {
        if(!$route)
            throw new EmptyRouteException();
        
        $routeObject = static::getRouteByName($route);

        return is_null( $routeObject ) ? url($route, $parameters) : route($route, $parameters);
    }

    public static function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }

    public static function getRouteObject($route, $parameters = null)
    {
        $routeObject = static::getRouteByName($route);

        return is_null($routeObject) ? collect(\Route::getRoutes())->first(function($r) use($route, $parameters){
            return $r->matches(request()->create(url($route, $parameters)));
        }) : $routeObject;
    }
}