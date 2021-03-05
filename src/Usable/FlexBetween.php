<?php

namespace Kompo;

class FlexBetween extends Flex
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->justifyBetween();
    }
}
