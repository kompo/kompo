<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Date extends Field
{
    public $vueComponent = 'Date';

    protected $dbFormat = 'Y-m-d';
    protected $configFormatKey = 'default_date_format';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->icon('icon-calendar');

        $this->setDbFormat();
        
        $this->dateFormat($this->configDateFormat());
    }

    /**
     * Sets a FlatPickr accepted alt format. By default, it's 'Y-m-d'.
     *
     * @param      string  $dateFormat  The date format
     *
     * @return self   
     */
    public function dateFormat($dateFormat)
    {
    	$this->config([
            'altFormat' => $dateFormat
        ]);
    	return $this;
    }

    /**
     * Sets the mode of the FlatPickr instance. 
     *
     * @param string  $dateMode  The date mode, ex: 'range'
     *
     * @return self   
     */
    public function dateMode($mode)
    {
        $this->config([
            'dateMode' => $mode
        ]);
        return $this;
    }

    /** PROTECTED ***/

    protected function setDbFormat()
    {
        return $this->config([
            'dateFormat' => $this->dbFormat
        ]);
    }

    protected function configDateFormat()
    {
        return config('kompo.'.$this->configFormatKey) ?: $this->dbFormat;
    }

}
