<?php

namespace Kompo;

class ButtonGroup extends LinkGroup
{
    protected function configureButtonGroup()
    {
        $this->class('vlButtonGroup');

        $this->containerClass('flex justify-between');

        $this->optionClass('cursor-pointer');

        $this->selectedClass('font-bold bg-primary-light');
    }
}
