<?php

namespace Kompo;

class MultiCheckbox extends MultiSelect
{
    public $vueComponent = 'MultiCheckbox';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
    }

    public function horizontal()
    {
        return $this->class('vlHorizontalMutiCheckbox');
    }
}
