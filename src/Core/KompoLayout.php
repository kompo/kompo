<?php

namespace Kompo\Core;

class KompoLayout
{
    protected $navbar;
    protected $lsidebar;
    protected $rsidebar;
    protected $footer;

    protected $isFixed = [];
    protected $order = [];
    protected $isAvailable = [];

    protected $hasAnyFixedMenus = false;
    protected $overFlowSet = false;

    protected $mainClass;
    protected $mainStyle;

    public function __construct($n, $l, $r, $f, $options = [])
    {
        $this->setMenu($n, 'navbar', 'vl-nav', 'vl-nav');
        $this->setMenu($l, 'lsidebar', 'vl-sidebar-l', 'vl-aside', true);
        $this->setMenu($r, 'rsidebar', 'vl-sidebar-r', 'vl-aside', true);
        $this->setMenu($f, 'footer', 'vl-footer', 'vl-footer');

        $this->hasAnyFixedMenus = count($this->isFixed) > 0;

        collect($options)->each(function($option, $key){
            $this->{$key} = $option;
        });
    }

    protected function setMenu($menu, $key, $menuClass, $menuTag, $menuCollapse = false)
    {
        //Setting the default kompo class
        if ($menu) {
            $menu->config([
                'menuClass'    => $menuClass,
                'menuTag'      => $menuTag,
                'menuCollapse' => property_exists($menu, 'collapse') ? $menu->collapse : $menuCollapse,
            ]);
        }

        //Set Menu
        $this->{$key} = $menu;

        //Check fixed
        if (optional($menu)->fixed) {
            $this->isFixed[$key] = true;
        }

        //Set order
        $this->order[$key] = optional($menu)->order;

        //Check if available
        if ($menu) {
            $this->isAvailable[$key] = true;
        }
    }

    public function getFirstKey($menuKey)
    {
        return [
            'menuKey' => strpos($menuKey, '|') ? substr($menuKey, 0, strpos($menuKey, '|')) : $menuKey,
        ];
    }

    public function getLastKey($menuKey)
    {
        return [
            'menuKey' => strpos($menuKey, '|') ? substr($menuKey, strpos($menuKey, '|') + 1) : $menuKey,
        ];
    }

    public function getLayoutKey()
    {
        return [
            'menuKey' => $this->getPrimaryMenu(),
        ];
    }

    public function wrapperOpenTag($appId = false)
    {
        $pm = $this->getPrimaryMenu();

        $wrapperClasses = $this->getFlexClass($pm).$this->getOverflowClass($appId);

        $tag = $pm ? ('<div class="'.$wrapperClasses.'"') : $this->getMainOpenTag($wrapperClasses);

        $tag .= $appId ? ' id="'.$appId.'" v-cloak' : '';

        return $tag.'>';
    }

    public function wrapperCloseTag()
    {
        $pm = $this->getPrimaryMenu();

        return $pm ? '</div>' : '</main>';
    }

    protected function getFlexClass($pm)
    {
        return in_array($pm, ['navbar', 'footer', 'navbar|footer']) ? 'kompoFlexCol' : ($pm ? 'kompoFlex' : 'vlFlex1');
    }

    protected function getOverflowClass($appId = false)
    {
        $overflow = !$appId ? '' : ($this->hasAnyFixedMenus ? 'vl100vh ' : 'vlMin100vh ');

        if ($this->hasAnyFixedMenus && !$this->overFlowSet) {
            $overflow .= $this->noFixedMenusLeft() ? 'kompoScrollableContent' : 'kompoFixedContent';
        }

        return $overflow ? (' '.$overflow) : '';
    }

    public function getPrimaryMenu()
    {
        foreach ([1, 2, 3] as $o) {
            if (!$this->check('lsidebar', $o) && $this->check('rsidebar', $o)) {
                return 'rsidebar';
            }
            if ($this->check('lsidebar', $o) && $this->check('rsidebar', $o)) {
                return 'lsidebar|rsidebar';
            }
            if ($this->check('lsidebar', $o) && !$this->check('rsidebar', $o)) {
                return 'lsidebar';
            }
            if (!$this->check('navbar', $o) && $this->check('footer', $o)) {
                return 'footer';
            }
            if ($this->check('navbar', $o) && $this->check('footer', $o)) {
                return 'navbar|footer';
            }
            if ($this->check('navbar', $o) && !$this->check('footer', $o)) {
                return 'navbar';
            }
        }

        //if the user has not defined a primary menu, use the order below
        if ($this->softCheck('navbar')) {
            return 'navbar';
        }
        if ($this->softCheck('lsidebar')) {
            return 'lsidebar';
        }
        if ($this->softCheck('rsidebar')) {
            return 'rsidebar';
        }
        if ($this->softCheck('footer')) {
            return 'footer';
        }
    }

    protected function check($key, $order)
    {
        return $this->softCheck($key) && ($this->order[$key] == $order);
    }

    protected function softCheck($key)
    {
        return $this->{$key} && $this->isAvailable($key);
    }

    protected function noFixedMenusLeft()
    {
        $remainingFixed = collect($this->isAvailable)->filter(function ($isAvailable, $key) {
            return $isAvailable && ($this->isFixed[$key] ?? false);
        })->count();

        if ($remainingFixed == 0) {
            $this->overFlowSet = true;

            return true;
        }

        return false;
    }

    protected function isAvailable($key)
    {
        return $this->isAvailable[$key] ?? false;
    }

    public function notAvailable($key)
    {
        $this->isAvailable[$key] = false;
    }

    public function hasAnySidebar()
    {
        return $this->has('lsidebar') || $this->has('rsidebar');
    }

    public function collapse($key)
    {
        return $this->{$key}->config('menuCollapse');
    }

    /********************************************
    ****** HTML ATTRIBUTES **********************
    ********************************************/

    public function has($key)
    {
        return $this->{$key} ? true : false;
    }

    public function getOpenTag($key)
    {
        return '<'.$this->{$key}->config('menuTag').$this->getMenuHtmlAttributes($key).'>'.
            $this->getOpenContainer($this->{$key});
    }

    public function getCloseTag($key)
    {
        $tag = $this->getCloseContainer($this->{$key}).
            '</'.$this->{$key}->config('menuTag').'>';
        //$this->{$key} = false; //we remove the menu since it finished rendering
        $this->notAvailable($key);

        return $tag;
    }

    public function getKomponentsArray($key)
    {
        return [
            'kompoid'    => KompoId::getFromElement($this->{$key}),
            'kompoinfo'  => KompoInfo::getFromElement($this->{$key}),
            'komponents' => $this->{$key}->komponents,
        ];
    }

    protected function getMenuHtmlAttributes($key)
    {
        $menu = $this->{$key};

        return $this->getVKompoAttribute($menu).
            /* TODELETE 
            $this->getClassAttribute($menu).
            $this->getIdAttribute($menu).
            $this->getStyleAttribute($menu).*/
            ($this->isSidebar($menu) ? $this->getSidebarSideAttribute($key) : '');
    }

    protected function getVKompoAttribute($menu)
    {
        return ' :vkompo="'.htmlspecialchars($menu).'"';
    }

    protected function getSidebarSideAttribute($key)
    {
        return ' side="'.($key == 'rsidebar' ? 'right' : 'left').'"';
    }

    protected function getOpenContainer($menu)
    {
        return $this->hasContainer($menu) ?
            ('<div class="'.$menu->config('menuClass').' '.$menu->containerClass.'">') : '';
    }

    protected function getCloseContainer($menu)
    {
        return $this->hasContainer($menu) ? '</div>' : '';
    }

    protected function hasContainer($menu)
    {
        return property_exists($menu, 'containerClass') ? $menu->containerClass : false;
    }

    protected function isSidebar($menu)
    {
        return strpos($menu->config('menuClass'), 'sidebar') > -1;
    }

    protected function getMainOpenTag($wrapperClasses)
    {
        $mainClassStr = ' class="'.$wrapperClasses.($this->mainClass ? (' '.$this->mainClass) : '').'"';
        $mainStyleStr = $this->mainStyle ? (' style="'.$this->mainStyle.'"') : '';

        return '<main'.$mainClassStr.$mainStyleStr;
    }
}
