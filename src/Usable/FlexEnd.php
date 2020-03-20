<?php

namespace Kompo;

use Kompo\Flex;

class FlexEnd extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyEnd();
    }
}
