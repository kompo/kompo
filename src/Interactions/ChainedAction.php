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
     * Sets a polling interval for the action. The action will be re-executed every $intervalMs milliseconds.
     *
     * @param int $intervalMs The interval in milliseconds
     *
     * @return self
     */
    public function every($intervalMs)
    {
        $this->action->config(['pollingInterval' => $intervalMs]);

        return $this;
    }

    /**
     * Delays the action execution by $delayMs milliseconds.
     *
     * @param int $delayMs The delay in milliseconds
     *
     * @return self
     */
    public function after($delayMs)
    {
        $this->action->config(['actionDelay' => $delayMs]);

        return $this;
    }

    /**
     * Throttle the action execution to at most once per $intervalMs milliseconds.
     *
     * @param int $intervalMs The throttle interval in milliseconds
     *
     * @return self
     */
    public function throttle($intervalMs)
    {
        $this->action->config(['throttle' => $intervalMs]);

        return $this;
    }

    /**
     * Automatically show a loading skeleton in the target panel during the server request,
     * and hide it when the response arrives.
     *
     * @param string $panelId The panel ID to show loading state in
     *
     * @return self
     */
    public function withLoadingIn($panelId)
    {
        $this->action->config(['loadingPanel' => $panelId]);

        return $this;
    }

    /**
     * Execute chained actions immediately (optimistically) before the server responds.
     * If the server returns an error, the changes are rolled back.
     *
     * @return self
     */
    public function optimistic()
    {
        $this->action->config(['optimistic' => true]);

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
