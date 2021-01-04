<?php

namespace Kompo\Routing;

use Kompo\Routing\Dispatcher;

class Router
{
    /**
     * Check to hold off booting the Komposer from it's constructor because the request has not been handled yet
     *
     * @return     bool
     */
    public static function shouldNotBeBooted(): bool
    {
        return 
            (request()->route() && !request()->hasSession()) ;
            //|| app()->runningInConsole(); //TODO: a route:list does not work currently...
    }

    /**
     * Gets the merged layout.
     *
     * @param      <type>  $route  The route
     *
     * @return  string  The merged layout.
     */
    public static function getMergedLayout($route): ?string
    {
        $layout = $route->action['layout'] ?? null;

        return is_array($layout) ? implode('.', $layout) : $layout;
    }

    /**
     * Gets the last section.
     *
     * @param      <type>  $route  The route
     *
     * @return  string  The last section.
     */
    public static function getLastSection($route): ?string
    {
        $section = $route->action['section'] ?? 'content';
        
        return is_array($section) ? end($section) : $section; //get last section only
    }
}