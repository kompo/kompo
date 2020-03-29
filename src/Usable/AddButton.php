<?php

namespace Kompo;

use Kompo\AddLink;

class AddButton extends AddLink
{
    public $linkTag = 'vlButton';
    
	protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);
		$this->outlined();
	}
}