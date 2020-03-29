<?php 

namespace Kompo\Interactions\Traits;

use Closure;
use Kompo\Exceptions\NotAcceptableInteractionClosureException;
use Kompo\Exceptions\NotAcceptableInteractionException;
use Kompo\Interactions\ActionGroup;

trait HasInteractions
{
    public $interactions = [];

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

}