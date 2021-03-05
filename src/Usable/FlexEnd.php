<?php

namespace Kompo;

class FlexEnd extends Flex
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->justifyEnd();
    }
}
