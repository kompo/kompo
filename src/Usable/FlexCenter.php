<?php

namespace Kompo;

class FlexCenter extends Flex
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->justifyCenter();
    }
}
