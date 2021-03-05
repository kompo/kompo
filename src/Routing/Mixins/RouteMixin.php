<?php

namespace Kompo\Routing\Mixins;

use Kompo\Exceptions\RouteLayoutIncorrectlySetException;

class RouteMixin
{
    public function section()
    {
        return function ($section) {
            $this->action['section'] = $section;

            return $this;
        };
    }

    public function layout()
    {
        return function ($layout) {
            throw new RouteLayoutIncorrectlySetException($this->uri);

            return $this;
        };
    }
}
