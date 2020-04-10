<?php

namespace Kompo;

use Kompo\AddLink;

class EditLink extends AddLink
{
	protected function setDefaultIcon()
	{
		if(!$this->label) //just an icon
			$this->icon('icon-edit');
	}
}