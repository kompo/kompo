<?php

namespace Kompo\Interactions;

use Kompo\Interactions\Action;
use Kompo\Interactions\Interaction;
use Kompo\Interactions\Traits\HasInteractions;

class ActionGroup
{
    use HasInteractions;

    public $chainedActions = [];

    public $element;
    
    public function __construct($element)
    {
        $this->element = $element;
    }

    public static function appendFromClosure($activeElement, $interactionType, $closure, $initialElement)
    {
        $actionGroup = new static($initialElement);
        call_user_func($closure, $actionGroup);

        if($activeElement instanceOf ChainedAction)
            $activeElement = $activeElement->action;

        foreach ($actionGroup->chainedActions as $chainedAction) {

            in_array($interactionType, ['success', 'error']) ? 

                Interaction::addToLastNested($activeElement, $chainedAction->action, $interactionType) :

                Interaction::appendToWithAction($activeElement, $chainedAction->action, $interactionType);

        }
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
        $chainedAction = new ChainedAction($this->element);
        $chainedAction->action = new Action($this->element, $methodName, $parameters);
        $this->chainedActions[] = $chainedAction;
        return $chainedAction;
    }
    
}