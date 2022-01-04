<?php

namespace Kompo\Routing;

use Illuminate\Support\Arr;
use Kompo\Core\KompoTarget;
use Kompo\Exceptions\EmptyRouteException;
use Kompo\Komponents\Komponent;

class RouteFinder
{
    /**
     * Guesses the desired route and method from the parameters.
     *
     * @param string $route
     * @param mixed  $parameters
     *
     * @return mixed
     */
    public static function matchRoute($route, $parameters = null)
    {
        if (!$route) {
            throw new EmptyRouteException();
        }

        return is_null(static::getRouteByName($route)) ?
                    url($route, $parameters) :
                    route($route, $parameters);
    }

    /**
     * Adds the main kompo route point to the element.
     *
     * @param mixed $kompo A kompo object
     *
     * @return self
     */
    public static function activateRoute($kompo)
    {
        return $kompo->config([
            'kompoRoute' => static::getKompoRoute(),
        ]);
    }

    /**
     * { function_description }.
     *
     * @param <type> $element       The element
     * @param string $routeOrKomponent The route or komponent class
     */
    public static function setUpKomponentRoute($element, $routeOrKomponent, $routeMethod = 'GET')
    {
        if (is_a($routeOrKomponent, Komponent::class, true)) {
            return $element->config(array_merge(
                [
                    'route'       => RouteFinder::getKompoRoute(),
                    'routeMethod' => $routeMethod,
                ],
                KompoTarget::getEncryptedArray($routeOrKomponent)
            ));
        }

        return $element->config([
            'route'       => $routeOrKomponent,
            'routeMethod' => $routeMethod,
        ]);
    }

    /**
     * Guesses the desired route and method from the parameters.
     *
     * @param string $route
     * @param mixed  $parameters
     *
     * @return mixed
     */
    public static function guessRoute($route, $parameters = null, $payload = null, $routeMethod = null)
    {
        if (!$route) {
            throw new EmptyRouteException();
        }

        if (filter_var($route, FILTER_VALIDATE_URL) !== false || $route === 'javascript:void(0)') {
            return $route;
        }

        $routeObject = static::getRouteByName($route);

        return is_null($routeObject) ? url($route, $parameters) : route($route, $parameters);

        /* To delete ? Payload addition for GET is now managed in JS
        return static::appendQueryString(
            is_null( $routeObject ) ? url($route, $parameters) : route($route, $parameters),
            $routeMethod,
            $payload
        );*/
    }

    /**
     * Gets the route object.
     *
     * @param <type> $route      The route
     * @param <type> $parameters The parameters
     *
     * @return <type> The route object.
     */
    public static function getRouteObject($route, $parameters = null)
    {
        $routeObject = static::getRouteByName($route);

        return is_null($routeObject) ? collect(\Route::getRoutes())->first(function ($r) use ($route, $parameters) {
            return $r->matches(request()->create(url($route, $parameters)));
        }) : $routeObject;
    }

    /**
     * Finds a route by komponent.
     *
     * @param      <type>  $komponentClass  The komponent class
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public static function findRouteByKomponent($komponentClass)
    {
        return collect(\Route::getRoutes())->first(function ($r) use ($komponentClass) {
            return ($r->action['controller'] ?? null) == $komponentClass;
        });
    }

    /**
     * The default route that handles Kompo requests.
     *
     * @return string.
     */
    public static function getKompoRoute($requestType = 'POST', $ajaxPayload = [])
    {
        return url('_kompo');

        /* To delete ? Payload addition for GET is now managed in JS
        return static::appendQueryString(
            url('_kompo'),
            $requestType,
            $ajaxPayload
        );*/
    }

    /**
     * Returns thr current route's parameters.
     *
     * @return <type> The route parameters.
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
     * @param <type> $route The route
     *
     * @return <type> The route by name.
     */
    protected static function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }

    protected static function appendQueryString($url, $method = null, $payload = null)
    {
        return $url.(($method == 'GET' && count($payload ?: [])) ? ('?'.Arr::query($payload)) : '');
    }
}
