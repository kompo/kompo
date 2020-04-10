<?php

namespace Kompo;

use Kompo\Komponents\Traits\ModalLinks;
use Kompo\Link;

class AddLink extends Link
{
	use ModalLinks;

    public $component = 'EditLink';
    public $linkTag = 'vlLink';

	protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->setDefaultSuccessAction();

        $this->setDefaultIcon();
	}

	protected function setDefaultIcon()
	{
		$this->icon('icon-plus');
	}
}