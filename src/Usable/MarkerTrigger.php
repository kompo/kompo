<?php

namespace Kompo;

use Kompo\Elements\Trigger;

class MarkerTrigger extends Trigger
{
    public $vueComponent = 'MarkerTrigger';
    
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->draggable(true);
    }

    public function draggable($draggable = true)
    {
        return $this->config([
            'draggable' => $draggable
        ]);
    }

    public function icon($icon)
    {
        return $this->config([
            'icon' => $icon
        ]);
    }

    public function lat($lat)
    {
        return $this->config([
            'lat' => $lat
        ]);
    }

    public function lng($lng)
    {
        return $this->config([
            'lng' => $lng
        ]);
    }
}
