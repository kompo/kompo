<?php

namespace Kompo;

use Kompo\Flex;

class FlexAround extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyAround();
    }
}
