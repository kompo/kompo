<?php

namespace Kompo;

use Kompo\Link;

class EditLink extends Link
{    
    public $component = 'EditLink';
    public $linkTag = 'vlLink';

	protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

		if(!$label) //just an icon
			$this->icon('icon-edit');
	}

    public function mounted($form)
    {
        $this->onSuccess(function($e) {
    		$e->emitDirect('insertForm');
    	});
    }

}