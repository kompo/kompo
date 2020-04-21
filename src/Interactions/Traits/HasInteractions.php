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
        'onClick', 'onChange', 'onBlur', 'onInput', 'onEnter', 
        'onSuccess', 'onError', 
    ];

    /**
    * Dynamically access collection proxies.
    *
    * @param  string  $key
    * @return mixed
    *
    * @throws \Exception
    */
    public function __get($key)
    {
        if(in_array($key, static::$proxies))
            return new HigherOrderInteraction($this, $key);
    }

    /**
     * Adds an interaction (trigger) to the element.
     * 
     * @param  string|array  $interactions
     * @param  mixed  $parameters
     *
     * @return mixed
     */
    public function on($interactions, $closure)
    {
        if(!is_string($interactions) && !is_array($interactions))
            throw new NotAcceptableInteractionException($interactions);

        collect( (array) $interactions )->each(function($interaction) use($closure) {

            Interaction::checkIfAcceptable($this, $interaction);
            
            if($closure instanceof Closure && is_callable($closure))
                return ActionGroup::appendFromClosure($this, $interaction, $closure, $this->element ?? $this);

            throw new NotAcceptableInteractionClosureException($closure);
        });

        return $this;
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

    /**
     * Sets the debounce interval for an action. Otherwise, it is defaulted to 500ms
     *
     * @param integer|null $debounce The number of milliseconds between requests.
     *
     * @return     self 
     */
    public function debounce($debounce = 500)
    {
        return $this->data([
            'debounce' => $debounce
        ]);
    }

}