<?php

namespace Kompo;

use Kompo\Date;

class DateRange extends Date
{
    public $vueComponent = 'Date';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->dateMode('range');
    }

    public function setOutput($value, $key)
    {
    	if($value)
    		$this->value[$key] = $value; //on output, don't override if no value in DB was found
    }

    public function setInput($value, $key)
    {
    	$this->value[$key] = $value; //on input, value can get overiden
        return $value;
    }

    public function value($dates)
    {
    	foreach($dates as $key => $value){
    		$this->value[$key] = $value;
    	}
    	return $this;
    }
}
