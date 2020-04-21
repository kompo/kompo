<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Time extends Field
{
    public $vueComponent = 'Date';

    protected $savedAsDateTime = false;

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->icon('icon-clock');

        $this->timeFormat(config('kompo.default_time_format') ?: 'H:i');

        $this->data([
            'enableTime' => true,
            'noCalendar' => true
        ]);
    }

    /**
     * Sets a FlatPickr accepted time format. By default, it's 'H:i'.
     *
     * @param      string  $timeFormat  The time format.
     *
     * @return self   
     */
    public function timeFormat($timeFormat = 'H:i')
    {
    	$this->data([
            'altFormat' => $timeFormat,
            'dateFormat' => $timeFormat,
        ]);
    	return $this;
    }

    /**
     * If your DB column is a DATETIME, use this method to convert it to that type.
     *
     * @return self 
     */
    public function savedAsDateTime()
    {
    	$this->savedAsDateTime = true;
    	return $this;
    }

    public function value($value)
    {
        if(!$this->savedAsDateTime){
            $this->value = $value;
        }else{
            if($this->validateDate($value, 'Y-m-d H:i:s')){
                $this->value = date($this->getTimeFormat(), strtotime($value)); //get TIME from DATETIME
            }
            if($this->validateDate($value, $this->getTimeFormat())) {
                $this->value = '1900-01-01 '.$value; //save as DATETIME in DB
            }
        }
    }


    private function validateDate($date, $format = 'Y-m-d')
	{
	    $d = \DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) === $date;
	}

	private function getTimeFormat()
	{
		return $this->data('altFormat') ?: 'H:i';
	}

}
