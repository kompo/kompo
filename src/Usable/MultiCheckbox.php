<?php

namespace Kompo;

class MultiCheckbox extends MultiSelect
{
    public $vueComponent = 'MultiCheckbox';

    protected function initialize($label)
    {
        parent::initialize($label);
    }

    public function horizontal()
    {
        return $this->class('vlHorizontalMutiCheckbox');
    }
}
