<?php

namespace Kompo;

class Liste extends Input
{	
    public $component = 'Liste';

    const DEFAULT_KEY_LABEL = 'key';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('text');
        $this->keyLabel(self::DEFAULT_KEY_LABEL);
    }

    public function mounted($form)
    {
        $this->data(['emptyValue' => $this->emptyValue()]);
    }

    protected function emptyValue()
    {
    	return [[$this->data('keyLabel') => '']];
    }

    protected function setValue($value)
    {
        $this->value = is_string($value) ? json_decode($value, true) :  $value;
    }

    /**
     * Sets the key label for the JSON string that will be encoded in the DB.
     * The default is 'key'.
     *
     * @param      string  $keyLabel  The key label for the json Object.
     *
     * @return     self  
     */
    public function keyLabel($keyLabel)
    {
    	$this->data(['keyLabel' => $keyLabel]);

    	return $this;
    }

}
