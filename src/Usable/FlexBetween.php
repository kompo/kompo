<?php

namespace Kompo;

class FlexBetween extends Flex
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->justifyBetween();
    }
}
