<?php

namespace Kompo;

use Kompo\Elements\Layout;
use Kompo\Elements\Managers\LayoutManager;

class Tab extends Layout
{
    public $vueComponent = 'FormTab';

    public function __construct(...$args)
    {
        if (count($args) == 1 && is_string($args[0])) {

            $this->initialize($args[0]);

        } else {

            parent::__construct($args);

        }
    }

    public function content(...$args)
    {
        $this->setElementsFromArguments($args);

        return $this;
    }

    
    protected function setElementsFromArguments($args)
    {

        $this->elements = LayoutManager::collectFilteredElements($args, $this)->values()->all();        
    }

    public function disabled()
    {
        return $this->config([
            'tabDisabled' => true,
        ]);
    }
}
