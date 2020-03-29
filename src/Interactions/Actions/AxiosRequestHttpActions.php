<?php

namespace Kompo\Interactions\Actions;

use Kompo\Routing\RouteFinder;

trait AxiosRequestHttpActions
{

    public function routeHttpRequest($routeMethod, $route, $parameters = null, $ajaxPayload = null)
    {
        return $this->prepareAxiosRequest([
            'route' => RouteFinder::guessRoute($route, $parameters),
            'routeMethod' => $routeMethod,
            'ajaxPayload' => $ajaxPayload
        ]);
    }

    /**
     * Loads a view with a GET ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->getView('get-route-of-view')->inModal()</php>
     *
     * @param      string  $route    The route name or uri.
     * @param      array|null  $parameters   The route parameters (optional).
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function get($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('GET', $route, $parameters, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param      string  $route    The route name or uri.
     * @param      array|null  $parameters   The route parameters (optional).
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function post($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('POST', $route, $parameters, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param      string  $route    The route name or uri.
     * @param      array|null  $parameters   The route parameters (optional).
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function put($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('PUT', $route, $parameters, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param      string  $route    The route name or uri.
     * @param      array|null  $parameters   The route parameters (optional).
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function delete($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('DELETE', $route, $parameters, $ajaxPayload);
    }
    
}