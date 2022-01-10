<?php

namespace Kompo\Elements\Traits;

trait HasOptionsClass
{
    /**
     * Add a class to the option wrapper.
     *
     * @param string $class The class
     *
     * @return self
     */
    public function optionClass($class)
    {
        return $this->config([
            'optionClass' => $class,
        ]);
    }

    /**
     * Add a class to the option's label.
     *
     * @param string $class The class
     *
     * @return self
     */
    public function optionLabelClass($class)
    {
        return $this->config([
            'optionLabelClass' => $class,
        ]);
    }
}
