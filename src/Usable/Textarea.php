<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Textarea extends Field
{
    public $component = 'Textarea';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->rows(3);
    }

    /**
     * Sets the initial number of rows for the textarea. Default is 3.
     *
     * @param      integer  $rows   The number of rows
     *
     * @return     self    
     */
    public function rows($rows =  3)
    {
    	return $this->data([
            'rows' => $rows
        ]);
    }

}
