<?php

namespace Kompo\Interactions\Traits;

use BadMethodCallException;
use Kompo\Interactions\Action;
use Kompo\Interactions\Interaction;

trait ForwardsInteraction
{
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
        if (method_exists(new Action(), $methodName)) {
            Interaction::addToLastNested($this, new Action($this, $methodName, $parameters));

            return $this;
        } else {
            if (is_callable('parent::__call')) {
                return parent::__call($methodName, $parameters);
            }

            throw new BadMethodCallException('Method '.static::class.'::'.$methodName.' does not exist.');
        }
    }
}
