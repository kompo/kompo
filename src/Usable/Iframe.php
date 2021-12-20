<?php

namespace Kompo;

use Kompo\Komponents\Block;
use Kompo\Routing\RouteFinder;

class Iframe extends Block
{
    public $vueComponent = 'Iframe';

    /**
     * The iframe's src.
     *
     * @var string
     */
    public $src = 'about:blank';

    //TODO document
    public function src($route, $parameters = null)
    {
        $this->src = RouteFinder::guessRoute($route, $parameters);

        return $this;
    }
}
