<?php 
namespace Kompo\Elements\Traits;

use Illuminate\Support\Arr;
use Kompo\Elements\Exceptions\BadTriggerDefinitionException;
use Kompo\Elements\Exceptions\TriggerNotAllowedException;
use Vuravel\Elements\Action;

trait HasTriggers
{

    protected static $allowedTriggers = [
        'load',
        'click',
        'focus',
        'blur',
        'mounted',
        'keyup',
        'keydown',
        'enter', //to remove and use with keyup.enter or keydown.enter
        'mouseover',
        'mouseleave',
        'change',
        'input',
        'success',
        'error'
    ];

    public $triggers = [];

    protected $defaultTrigger = 'click';

    protected function defaultTrigger()
    {
        if(!Arr::get($this->triggers, $this->defaultTrigger) )
            $this->triggers[$this->defaultTrigger][] = Action::emptyAjax($this); //pushing empty ajax action trigger

        return collect($this->triggers[$this->defaultTrigger])->last(function($trigger){
            return $trigger->isAjax();
        });
    }

    protected function updateDefaultTrigger($function)
    {
        $oldTrigger = $this->defaultTrigger();
        $this->triggers[$this->defaultTrigger] = [];

        $defaultMethod = 'on'.ucfirst($this->defaultTrigger);
        $this->{$defaultMethod}($function);
        $this->triggers[$this->defaultTrigger][0]->triggers = $oldTrigger->triggers;
        return $this;
    }

    /**
     * Adds a trigger to the element.
     * @param  string|array  $triggers
     * @param  mixed  $parameters
     *
     * @return mixed
     */
    public function on($triggers, $function)
    {
        if(!is_string($triggers) && !is_array($triggers))
            throw (new BadTriggerDefinitionException)->setMessage(gettype($triggers));

        collect( (array)$triggers )->each(function($trigger) use($function) {
            $this->addTrigger($trigger, $function);
        });

        return $this;
    }

    protected function addTrigger($trigger, $function)
    {
        if(!in_array($trigger, self::$allowedTriggers))
            throw (new TriggerNotAllowedException)->setMessage($trigger);

        $this->triggers[$trigger][] = Action::form($this, $function);
    }

    public function hasTriggers()
    {
        return count($this->triggers);
    }



    /*****************************************************************
    ************** Shortcut helpers for assigning triggers ***********
    ******************************************************************/
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
        //TODO: UNCOMMENT
        //return $this->on('enter', $function);
    }


}