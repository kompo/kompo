<?php

namespace Kompo;

use Kompo\Elements\TriggerWithSubmenu;

class Dropdown extends TriggerWithSubmenu
{
    public $vueComponent = 'Dropdown';
    public $bladeComponent = 'Dropdown';

    /**
     * The dropdown menu will align to the right instead of the default left alignment.
     *
     * @return self
     */
    public function alignRight()
    {
        return $this->config(['dropdownPosition' => 'vlDropdownMenuRight']);
    }

    public function alignUpRight()
    {
        return $this->config([
            'dropdownPosition' => 'vlDropdownMenuUpRight',
        ]);
    }

    //TODO DOCUMENT
    public function noBorder()
    {
        return $this->config([
            'menuNoBorder' => true,
        ]);
    }

    //TODO DOCUMENT
    public function button($additionalClass = '')
    {
        return $this->togglerClass('vlBtn justify-center'.($additionalClass ? ' '.$additionalClass : ''));
    }

    public function openOnClick()
    {
        return $this->config([
            'openOnClick' => true,
        ]);
    }
}
