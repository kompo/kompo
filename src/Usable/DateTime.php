<?php

namespace Kompo;

use Kompo\Date;

class DateTime extends Date
{
    protected $dbFormat = 'Y-m-d H:i:s';
    protected $configFormatKey = 'default_datetime_format';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->data([
            'enableTime' => true
        ]);
    }

}
