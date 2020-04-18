<?php

namespace Kompo\Interactions;

use Kompo\Interactions\Interaction;
use Kompo\Interactions\Traits\HasInteractions;

class ActionGroup
{
    use HasInteractions;

    public $chainedActions = [];

    public $element;

    protected $interactionType;

    protected $activeElement;
    
    public function __construct($element, $interactionType, $activeElement)
    {
        $this->element = $element;
        $this->interactionType = $interactionType;
        $this->activeElement = $activeElement instanceOf ChainedAction ? $activeElement->action : $activeElement;
    }

    public static function appendFromClosure($activeElement, $interactionType, $closure, $initialElement)
    {
        $actionGroup = new static($initialElement, $interactionType, $activeElement);

        call_user_func($closure, $actionGroup);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $methodName
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($methodName, $parameters)
    {
        return (new ChainedAction($this->element, $methodName, $parameters))
            ->handleInteraction($this->interactionType, $this->activeElement);
    }
    
}