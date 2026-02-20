<?php

namespace Kompo\Interactions\Traits;

use Closure;
use Exception;
use Kompo\Exceptions\NotAcceptableInteractionClosureException;
use Kompo\Exceptions\NotAcceptableInteractionException;
use Kompo\Interactions\ActionGroup;
use Kompo\Interactions\HigherOrderInteraction;
use Kompo\Interactions\Interaction;

trait HasInteractions
{
    public $interactions = [];

    protected static $proxies = [
        'onClick', 'onChange', 'onFocus', 'onBlur', 'onInput', 'onEnter',
        'onSuccess', 'onError', 'onEmit', 'onLoad', 'onSocketEventAction',
    ];

    /**
     * Dynamically access collection proxies.
     *
     * @param string $key
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!in_array($key, static::$proxies)) {
            throw new Exception("Property [{$key}] does not exist on this class.");
        }

        //if(in_array($key, static::$proxies)) //todelete. Previously no exception was raised when property wasn't found and we returned null.
        return new HigherOrderInteraction($this, $key);
    }

    /**
     * Adds an interaction (trigger) to the element.
     *
     * @param string|array $interactions
     * @param mixed        $parameters
     *
     * @return mixed
     */
    public function on($interactions, $closure)
    {
        if (!is_string($interactions) && !is_array($interactions)) {
            throw new NotAcceptableInteractionException($interactions);
        }

        collect((array) $interactions)->each(function ($interaction) use ($closure) {
            Interaction::checkIfAcceptable($this, $interaction);

            if ($closure instanceof Closure && is_callable($closure)) {
                return ActionGroup::appendFromClosure($this, $interaction, $closure, $this->getInitialElement());
            }

            throw new NotAcceptableInteractionClosureException($closure);
        });

        return $this;
    }

    protected function getInitialElement()
    {
        return property_exists($this, 'element') ? $this->element : $this;
    }

    public function onClick($function)
    {
        return $this->on('click', $function);
    }

    public function onSuccess($function)
    {
        return $this->on('success', $function);
    }

    public function onError($function)
    {
        return $this->on('error', $function);
    }

    public function onChange($function)
    {
        return $this->on('change', $function);
    }

    public function onEmit($function)
    {
        return $this->on('emit', $function);
    }

    public function onFocus($function)
    {
        return $this->on('focus', $function);
    }

    public function onBlur($function)
    {
        return $this->on('blur', $function);
    }

    public function onInput($function)
    {
        return $this->on('input', $function);
    }

    public function onEnter($function)
    {
        return $this->on('enter', $function);
    }

    public function onLoad($function)
    {
        return $this->on('load', $function);
    }

    public function onSocketEventAction($function)
    {
        return $this->on('socketEvent', $function);
    }

    /**
     * Sets the debounce interval for an action. Otherwise, it is defaulted to 500ms.
     *
     * @param int|null $debounce The number of milliseconds between requests.
     *
     * @return self
     */
    public function debounce($debounce = 500)
    {
        return $this->config([
            'debounce' => $debounce,
        ]);
    }
}
