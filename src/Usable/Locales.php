<?php

namespace Kompo;

use Kompo\Dropdown;
use Kompo\Link;

class Locales extends Dropdown
{
    public $bladeComponent = 'Dropdown';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: strtoupper(session('kompo_locale')));

    	$this->submenu(
            collect(config('kompo.locales'))->map(function($language, $locale){
				return Link::form($language)->href('setLocale',['locale' => $locale]);
			})
		)->alignRight();
    }

    /* TO REVIEW... NOT USER FRIENDLY*/
    public function horizontal()
    {
        return _Flex(
            collect(config('kompo.locales'))->map(function($language, $locale){
                return _Link($language)
                    ->href('setLocale',['locale' => $locale])
                    ->class(session('kompo_locale') == $locale ? '' : 'text-gray-400');
            })
        );
    }
}
