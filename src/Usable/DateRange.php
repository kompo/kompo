<?php

namespace Kompo;

use Kompo\Date;

class DateRange extends Date
{
    public $component = 'DateRange';

    public function setOutput($value, $key)
    {
    	if($value)
    		$this->value[$key] = $value; //on output, don't override if no value in DB was found
    }

    public function setInput($value, $key)
    {
    	$this->value[$key] = $value; //on input, value can get overiden
    }

    public function value($dates)
    {
    	foreach($dates as $key => $value){
    		$this->value[$key] = $value;
    	}
    	return $this;
    }
}
