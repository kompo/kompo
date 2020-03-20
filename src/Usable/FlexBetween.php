<?php

namespace Kompo;

use Kompo\Flex;

class FlexBetween extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyBetween();
    }
}
