<?php

namespace Kompo;

use Kompo\Date;

class DateTime extends Date
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->dateFormat(config('vuravel.default_datetime_format') ?: 'Y-m-d H:i');

        $this->data([
            'enableTime' => true
        ]);
    }

}
