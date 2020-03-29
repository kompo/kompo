<?php

namespace Kompo\Interactions;

use Kompo\Interactions\Traits\HasInteractions;

class ChainedAction
{
    use HasInteractions;

    public $element;

    public $action;
    
    public function __construct($element)
    {
        $this->element = $element;
    }

    /**
     * Handle dynamic static method calls into the class.
     *
     * @param  string  $methodName
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($methodName, $parameters)
    {
        Interaction::addToLastNested($this->action, new Action($this->element, $methodName, $parameters));

        return $this;
    }
    
}