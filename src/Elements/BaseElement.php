<?php

namespace Kompo\Elements;

use BadMethodCallException;
use Illuminate\Support\Traits\Macroable;

abstract class BaseElement
{
    use Traits\HasId;
    use Traits\HasClasses;
    use Traits\HasInternalConfig;
    use Traits\HasConfig;
    use Traits\HasStyles;
    use Traits\HasAnimation;
    use Traits\HasDuskSelector;
    use Traits\IsMountable;
    use Traits\ElementHelperMethods;
    use Traits\UsesLocale;
    use Macroable {
        __callStatic as protected __callStaticTrait;
        __call as protected __callTrait;
    }

    /**
     * The related Vue component name.
     *
     * @var string
     */
    public $vueComponent;

    /**
     * Prepares an element and passes important information for display.
     */
    abstract public function prepareForDisplay($komponent);

    /**
     * Prepares an element and passes important information for submit.
     */
    abstract public function prepareForAction($komponent);

    /**
     * A helpful way to construct a Kompo object and chain it additional methods.
     * ! Works for Komponents too - because ... handles variable # of arguments.
     *
     * @param mixed $arguments
     *
     * @return self
     */
    public static function form(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (in_array($method, static::duplicateStaticMethods())) {
            $method .= 'Static';

            return static::$method(...$parameters);
        }

        return $this->__callStaticTrait($method, $parameters);

        throw new BadMethodCallException('Method '.static::class.'::'.$method.' does not exist.');
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, static::duplicateStaticMethods())) {
            $method .= 'NonStatic';

            return $this->$method(...$parameters);
        }

        return $this->__callTrait($method, $parameters);

        throw new BadMethodCallException('Method '.static::class.'::'.$method.' does not exist.');
    }

    /**
     * Methods that can be called both statically or non-statically.
     *
     * @return array
     */
    public static function duplicateStaticMethods()
    {
        return [];
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
