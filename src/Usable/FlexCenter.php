<?php

namespace Kompo;

class FlexCenter extends Flex
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->justifyCenter();
    }
}
