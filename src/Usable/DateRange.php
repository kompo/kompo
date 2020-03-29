<?php

namespace Kompo;

use Kompo\Date;

class DateRange extends Date
{
    public $component = 'DateRange';

    public function prepareValueForFront($name, $value, $model)
    {
    	if($value)
        	$this->value[] = $value;
    }
}
