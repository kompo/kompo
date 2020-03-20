<?php

namespace Kompo;

use Kompo\Flex;

class FlexCenter extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyCenter();
    }
}
