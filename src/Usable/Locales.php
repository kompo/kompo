<?php

namespace Kompo;

use Kompo\Dropdown;
use Kompo\Link;

class Locales extends Dropdown
{
    public $menuComponent = 'Dropdown';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: strtoupper(session('locale')));

    	$this->submenu(
            collect(config('vuravel.locales'))->map(function($language, $locale){
				return Link::form($language)->href('setLocale',['locale' => $locale]);
			})
		)->alignRight();
    }
}
