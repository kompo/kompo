<?php

namespace Kompo;

use Kompo\Komponents\Trigger;

class SidebarToggler extends Trigger
{
    public $bladeComponent = 'SidebarToggler';
    
	protected function vlInitialize($label)
    {
    	parent::vlInitialize($label ?: '&#9776;');

		$this->toggleSidebar();
	}
 
	public function toggleSidebar($side = 'left')
	{
		return $this->config([
			'toggleSidebar' => $side
		]);
	}    
}
