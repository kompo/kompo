<?php

namespace Kompo\Elements\Traits;

use Kompo\Routing\RouteFinder;

trait DoesTurboRefresh
{
    /**
     * Flag for loading an element like turbolinks.
     *
     * @var bool
     */
    public $turbo = false;

    /**
     * Verifies if the href link should be loaded like turbolinks (no full page reload).
     *
     * @param string $route      [description]
     * @param array  $parameters [description]
     *
     * @return void
     */
    public function checkTurbo($route, $parameters = null)
    {
        if ($this->config('turboDisabled')) {
            return;
        }

        if (
            ($routeObject = RouteFinder::getRouteObject($route, $parameters)) && ($routeObject->action['layout'] ?? '')
            //(($routeObject->action['layout'] ?? '') === (request()->route()->action['layout'] ?? false))
        ) {
            $this->forceTurbo();
        }
    }

    /**
     * Flag for disabling turbo links.
     *
     * @var bool
     */
    public function noTurbo()
    {
        return $this->config([
            'turboDisabled' => true,
        ]);
    }

    /** TODO: Document
     * Force turbo for component href or redirect.
     *
     * @var bool
     */
    public function forceTurbo()
    {
        $this->turbo = true;

        return $this;
    }
}
