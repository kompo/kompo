<?php

namespace Kompo;

use Kompo\Link;

class AddLink extends Link
{
    public $component = 'EditLink';
    public $linkTag = 'vlLink';

    public function mounted($form)
    {
        $this->onSuccess(function($e) {
    		$e->emitDirect('insertForm');
    	});
    }


}