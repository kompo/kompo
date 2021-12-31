<?php

namespace Kompo;

class Locales extends Dropdown
{
    public $bladeComponent = 'Dropdown';

    protected function initialize($label)
    {
        parent::initialize($label ?: strtoupper(session('kompo_locale')));

        $this->submenu(
            collect(config('kompo.locales'))->map(function ($language, $locale) {
                return Link::form($language)->href('setLocale', ['locale' => $locale]);
            })
        )->alignRight();
    }

    /* TO REVIEW... NOT USER FRIENDLY*/
    public function horizontal()
    {
        return _Flex(
            collect(config('kompo.locales'))->map(function ($language, $locale) {
                return _Link($language)
                    ->href('setLocale', ['locale' => $locale])
                    ->class(session('kompo_locale') == $locale ? '' : 'text-gray-400');
            })
        );
    }
}
