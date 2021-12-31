<?php

namespace Kompo;

class FlexAround extends Flex
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->justifyAround();
    }
}
