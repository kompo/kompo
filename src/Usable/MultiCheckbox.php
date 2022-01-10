<?php

namespace Kompo;

use Kompo\Elements\Traits\HasOptionsClass;

class MultiCheckbox extends MultiSelect
{
    use HasOptionsClass;
    
    public $vueComponent = 'MultiCheckbox';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noInputWrapper();

        $this->labelClass('mb-2');

        $this->optionClass('mb-2');
    }

    /**
     * Make the MultiCheckbox options display horizontally.
     *
     * @return self
     */
    public function horizontal()
    {
        return $this->class('vlHorizontalMutiCheckbox');
    }
}
