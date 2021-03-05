<?php

namespace Kompo;

use Illuminate\Support\Carbon;

class DateTime extends Date
{
    protected $dbFormat = 'Y-m-d H:i:s';
    protected $configFormatKey = 'default_datetime_format';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->config([
            'enableTime' => true,
        ]);
    }

    //If a 'datetime' cast is added to the model's attribute,
    //we should remove any reference to timezone before outputting
    //Otherwise flatpickr changes it to a UTC date...
    public function setOutput($value, $key)
    {
        if (!is_null($value)) {
            $value = $value instanceof Carbon ? $value->format('Y-m-d H:i') : $value;
            $this->value($value);
        }
    }
}
