<?php

namespace Kompo;

class DateTime extends Date
{
    protected $dbFormat = 'Y-m-d H:i:s';
    protected $outputFormat = 'Y-m-d H:i';
    protected $configFormatKey = 'default_datetime_format';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->config([
            'enableTime' => true,
        ]);
    }
}
