<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Date extends Field
{
    public $component = 'Date';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->icon('icon-calendar');
        
        $this->dateFormat(config('kompo.default_date_format') ?: 'Y-m-d');
    }

    /**
     * Sets a FlatPickr accepted date format. By default, it's 'Y-m-d'.
     *
     * @param      string  $dateFormat  The date format
     *
     * @return self   
     */
    public function dateFormat($dateFormat)
    {
    	$this->data([
            'dateFormat' => $dateFormat,
            'altFormat' => $dateFormat
        ]);
    	return $this;
    }

}
