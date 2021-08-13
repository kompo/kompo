<?php

namespace Kompo\Interactions\Actions;

use Kompo\Routing\RouteFinder;

trait AxiosRequestHttpActions
{
    public function routeHttpRequest($routeMethod, $route, $parameters = null, $payload = null)
    {
        return $this->prepareAxiosRequest([
            'route'       => RouteFinder::guessRoute($route, $parameters, $payload, $routeMethod),
            'routeMethod' => $routeMethod,
            'ajaxPayload' => $payload,
        ]);
    }

    /**
     * Performs a GET request to a route in your application.
     *
     * @param string     $route       The route name or uri.
     * @param array|null $parameters  The route parameters (optional).
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function get($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('GET', $route, $parameters, $ajaxPayload);
    }

    /**
     * Performs a POST request to a route in your application.
     *
     * @param string     $route       The route name or uri.
     * @param array|null $parameters  The route parameters (optional).
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function post($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('POST', $route, $parameters, $ajaxPayload);
    }

    /**
     * Performs a PUT request to a route in your application.
     *
     * @param string     $route       The route name or uri.
     * @param array|null $parameters  The route parameters (optional).
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function put($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('PUT', $route, $parameters, $ajaxPayload);
    }

    /**
     * Performs a DELETE request to a route in your application.
     *
     * @param string     $route       The route name or uri.
     * @param array|null $parameters  The route parameters (optional).
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function delete($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->routeHttpRequest('DELETE', $route, $parameters, $ajaxPayload);
    }

    //TODO: document
    public function create($route, $parameters = null, $ajaxPayload = null)
    {
        $this->applyToElement(
            fn($el) => $el->class('cursor-pointer')->config(['refreshParent' => true])
        );

        return $this->get($route, $parameters, $ajaxPayload);
    }

    //TODO: document
    public function update($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->create($route, $parameters, $ajaxPayload);
    }
}
