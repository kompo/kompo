<?php

namespace Kompo;

use Kompo\Elements\Block;

class Chart extends Block
{
    public $vueComponent = 'Chartjs';

    //TODO: document
    //$options is an array of configurations to pass to the Chart(ctx, options) initialization.
    public function options($options)
    {
        return $this->config([
            'chartOptions' => $options,
        ]);
    }
}
