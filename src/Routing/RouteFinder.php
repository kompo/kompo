<?php

namespace Kompo\Routing;

use Kompo\Core\KompoTarget;
use Kompo\Exceptions\EmptyRouteException;
use Kompo\Komposers\Komposer;

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
            'kompoRoute' => static::getKompoRoute()
        ]);
    }

    /**
     * { function_description }
     *
     * @param <type>  $komponent        The komponent
     * @param string  $routeOrKomposer  The route or komposer class
     */
    public static function setUpKomposerRoute($komponent, $routeOrKomposer, $routeMethod = 'GET')
    {
        if(is_a($routeOrKomposer, Komposer::class, true))
            return $komponent->data([
                'route' => RouteFinder::getKompoRoute(),
                'routeMethod' => $routeMethod,
                'komposerClass' => KompoTarget::getEncrypted($routeOrKomposer)
            ]);

        return $komponent->data([
            'route' => $routeOrKomposer,
            'routeMethod' => $routeMethod
        ]);
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

    /**
     * Gets the route object.
     *
     * @param      <type>  $route       The route
     * @param      <type>  $parameters  The parameters
     *
     * @return     <type>  The route object.
     */
    public static function getRouteObject($route, $parameters = null)
    {
        $routeObject = static::getRouteByName($route);

        return is_null($routeObject) ? collect(\Route::getRoutes())->first(function($r) use($route, $parameters){
            return $r->matches(request()->create(url($route, $parameters)));
        }) : $routeObject;
    }

    /**
     * The default route that handles Kompo GET requests.
     *
     * @return string.
     */
    public static function getKompoRoute()
    {
        return route('_kompo');
    }

    /**
     * The default route that handles Kompo POST requests.
     *
     * @return string.
     */
    public static function postKompoRoute()
    {
        return route('_kompo.post');
    }

    /**
     * The default route that handles Kompo PUT requests.
     *
     * @return string.
     */
    public static function putKompoRoute()
    {
        return route('_kompo.put');
    }

    /**
     * The default route that handles Kompo DELETE requests.
     *
     * @return string.
     */
    public static function deleteKompoRoute()
    {
        return route('_kompo.delete');
    }


    /**
     * Returns thr current route's parameters
     *
     * @return     <type>  The route parameters.
     */
    public static function getRouteParameters()
    {
        return request()->route() ? request()->route()->parameters() : [];
        /* return array_replace(
            request()->all(),
            request()->route() ? request()->route()->parameters() : []
        );*/
    }

    /***** PROTECTED ****/

    /**
     * Gets the route by name.
     *
     * @param      <type>  $route  The route
     *
     * @return     <type>  The route by name.
     */
    protected static function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }
}