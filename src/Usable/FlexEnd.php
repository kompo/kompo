<?php

namespace Kompo;

class FlexEnd extends Flex
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->justifyEnd();
    }
}
