<?php

namespace Kompo\Interactions;

use Kompo\Interactions\Traits\HasInteractions;

class ChainedAction
{
    use HasInteractions;

    public $element;

    public $action;

    public function __construct($element, $methodName, $parameters)
    {
        $this->element = $element;

        $this->action = new Action($this->element, $methodName, $parameters);
    }

    public function handleInteraction($interactionType, $activeElement)
    {
        Interaction::placeInElement($activeElement, $this->action, $interactionType);

        return $this;
    }

    /**
     * Handle dynamic static method calls into the class.
     *
     * @param string $methodName
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($methodName, $parameters)
    {
        Interaction::addToLastNested($this->action, new Action($this->element, $methodName, $parameters));

        return $this;
    }
}
