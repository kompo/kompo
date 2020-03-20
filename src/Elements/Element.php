<?php

namespace Kompo\Elements;

use BadMethodCallException;
use Kompo\Elements\Traits\HasClasses;
use Kompo\Elements\Traits\HasConfig;
use Kompo\Elements\Traits\HasData;
use Kompo\Elements\Traits\HasId;
use Kompo\Elements\Traits\HasStyles;
use Kompo\Elements\Traits\HasTriggers;
use Kompo\Elements\Traits\IsMountable;

abstract class Element
{
    /**
     * TODO: Refactor component to vueComponent
     */
    use HasId, HasClasses, HasConfig, HasData, HasStyles, HasTriggers, IsMountable;
    
    /**
     * The related Vue component name.
     *
     * @var string
     */
    public $component;

    /**
     * Prepares an element and passes important information for display.
     */
    abstract public function prepareForDisplay($komposer);

    /**
     * Prepares an element and passes important information for submit.
     */
    abstract public function prepareForSave($komposer);

    /**
     * Handle dynamic method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if(in_array($method, static::duplicateStaticMethods())){
            $method .= 'Static';
            return (new static(true))->$method(...$parameters);
        }
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if(in_array($method, static::duplicateStaticMethods())){
            $method .= 'NonStatic';
            return $this->$method(...$parameters);
        }
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * Displays the rendered Vue component.
     * Mostly, useful when echoing in blade for example.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}