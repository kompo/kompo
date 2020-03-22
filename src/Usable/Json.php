<?php

namespace Kompo;

use Kompo\Liste;

class Json extends Liste
{
    public $component = 'Json';

    const DEFAULT_VALUE_LABEL = 'value';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->valueLabel(self::DEFAULT_VALUE_LABEL);
    }

    /**
     * Sets the value label for the JSON string that will be encoded in the DB.
     * The default is 'value'.
     *
     * @param      string  $valueLabel  The value label for the json Object.
     *
     * @return self  
     */
    public function valueLabel($valueLabel)
    {
    	$this->data(['valueLabel' => $valueLabel]);

    	return $this;
    }

    protected function emptyValue()
    {
    	return [[$this->data('keyLabel') => '', $this->data('valueLabel') => '']];
    }
}
