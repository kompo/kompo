<?php

namespace Kompo\Interactions\Actions;

use Kompo\Routing\RouteFinder;

trait RedirectActions
{
    /**
     * Redirects to the route/uri if the AJAX request was a success.
     *
     * @param string     $route      The route name or uri.
     * @param array|null $parameters The route parameters (optional).
     *
     * @return mixed
     */
    public function redirect($route = null, $parameters = null)
    {
        if ($route) { //when route is empty we redirect to the response.url
            $this->applyToElement(function ($el) use ($route, $parameters) {
                $el->checkTurbo($route, $parameters);
            });
        }

        return $this->prepareAction('redirect', [
            'redirectUrl' => $route ? RouteFinder::guessRoute($route, $parameters) : true,
        ]);
    }
}
