<?php

namespace Kompo;

class DateRange extends Date
{
    public $component = 'DateRange';

    public $value = [];

    public function prepareValueForFront($name, $value, $model)
    {
        $this->value[] = $value;
    }
}
