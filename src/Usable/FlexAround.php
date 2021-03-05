<?php

namespace Kompo;

class FlexAround extends Flex
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->justifyAround();
    }
}
