<?php

namespace Kompo\Routing\Mixins;

use Illuminate\Routing\RouteRegistrar;

class RouteRegistrarExtended extends RouteRegistrar
{
    public function __construct(\Illuminate\Routing\Router $router)
    {
        parent::__construct($router);

        $this->allowedAttributes = array_merge($this->allowedAttributes, ['layout', 'section']);

        return $this;
    }
}
