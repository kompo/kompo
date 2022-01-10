<?php

namespace Kompo;

use Kompo\Elements\Traits\HasOptionsClass;

class Radio extends Select
{
    use HasOptionsClass;
    
    public $vueComponent = 'Radio';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noInputWrapper();

        $this->optionClass('mb-2');

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
}
