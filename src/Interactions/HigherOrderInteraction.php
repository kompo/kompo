<?php

namespace Kompo\Interactions;

class HigherOrderInteraction
{
    protected $onInteraction;

    protected $element;

    public function __construct($element, $onInteraction)
    {
        $this->element = $element;
        $this->onInteraction = $onInteraction;
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
        return $this->element->{$this->onInteraction}(function($e) use($methodName, $parameters){
            $e->{$methodName}(...$parameters);
        });
    }


    
}