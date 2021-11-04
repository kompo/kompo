<?php

namespace Kompo;

class Radio extends Select
{
    public $vueComponent = 'Radio';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noInputWrapper();

        $this->optionLabelClass('cursor-pointer');
    }

    /**
     * Make the radio inputs and labels horizontal
     *
     * @return  self
     */
    public function horizontal()
    {
        return $this->class('vlHorizontalRadio');
    }

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
