<?php

namespace Kompo\Core;

use Kompo\Core\KompoInfo;

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

    public function __construct($n, $l, $r, $f)
    {
    	$this->setMenu($n, 'navbar', 'vl-nav', 'nav');
    	$this->setMenu($l, 'lsidebar', 'vl-sidebar-l', 'aside', true);
    	$this->setMenu($r, 'rsidebar', 'vl-sidebar-r', 'aside', true);
    	$this->setMenu($f, 'footer', 'vl-footer', 'footer');

    	$this->hasAnyFixedMenus = count($this->isFixed) > 0;
    }

	protected function setMenu($menu, $key, $menuClass, $menuTag, $menuCollapse = false)
	{
		//Setting the default kompo class
		if($menu)
			$menu->data([
				'menuClass' => $menuClass,
				'menuTag' => $menuTag,
				'menuCollapse' => property_exists($menu, 'collapse') ? $menu->collapse : $menuCollapse
			]);

		//Set Menu
		$this->{$key} = $menu;

		//Check fixed
		if(optional($menu)->fixed)
			$this->isFixed[$key] = true;

		//Set order
		$this->order[$key] = optional($menu)->order;

		//Check if available
		if($menu)
			$this->isAvailable[$key] = true;
	}

	public function getFirstKey($menuKey)
	{
		return [
			'menuKey' => strpos($menuKey, '|') ? substr($menuKey, 0, strpos($menuKey, '|')) : $menuKey
		];
	}

	public function getLastKey($menuKey)
	{
		return [
			'menuKey' => strpos($menuKey, '|') ? substr($menuKey, strpos($menuKey, '|') + 1) : $menuKey
		];
	}

	public function getLayoutKey()
	{
	    return [
	    	'menuKey' => $this->getPrimaryMenu()
	    ];
	}

	public function wrapperOpenTag($main = false)
	{
	    $pm = $this->getPrimaryMenu();

	    $flex = in_array($pm, ['navbar', 'footer', 'navbar|footer']) ? 
	    	'kompoFlexCol' : 
	    	($pm ? 'kompoFlex' : 'vlFlex1');

	    $tag = $pm ? '<div' : '<main';

	    $tag .= $main ? ' id="'.$main.'"': ''; //adding vue app id if main layout

	    $overflow = $main ? 'vl100vh ' : '';
	    if($this->hasAnyFixedMenus && !$this->overFlowSet)
	        $overflow .= $this->noFixedMenusLeft() ? 'kompoScrollableContent' : 'kompoFixedContent';

	    return $tag.' class="'.$flex.($overflow ? (' '.$overflow) : '').'">';
	}

	public function wrapperCloseTag()
	{
	    $pm = $this->getPrimaryMenu();

	    return $pm ? '</div>' : '</main>';
	}

    public function getPrimaryMenu()
	{
		foreach ([1,2,3] as $o) {
		    if(!$this->check('lsidebar', $o) && $this->check('rsidebar', $o)) return 'rsidebar';
		    if($this->check('lsidebar', $o) && $this->check('rsidebar', $o)) return 'lsidebar|rsidebar';
		    if($this->check('lsidebar', $o) && !$this->check('rsidebar', $o)) return 'lsidebar';
		    if(!$this->check('navbar', $o) && $this->check('footer', $o)) return 'footer';
		    if($this->check('navbar', $o) && $this->check('footer', $o)) return 'navbar|footer';
		    if($this->check('navbar', $o) && !$this->check('footer', $o)) return 'navbar';
		}

	    //if the user has not defined a primary menu, use the order below
	    if($this->softCheck('navbar')) return 'navbar';
	    if($this->softCheck('lsidebar')) return 'lsidebar';
	    if($this->softCheck('rsidebar')) return 'rsidebar';
	    if($this->softCheck('footer')) return 'footer';
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
		$remainingFixed = collect($this->isAvailable)->filter(function($isAvailable, $key){
			return $isAvailable && ($this->isFixed[$key] ?? false);
		})->count();

		if($remainingFixed == 0){
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
		return $this->{$key}->data('menuCollapse');
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
    	return '<'.$this->{$key}->data('menuTag').$this->getMenuHtmlAttributes($this->{$key}).'>'.
    		$this->getOpenContainer($this->{$key});
    }

    public function getCloseTag($key)
    {
    	$tag = $this->getCloseContainer($this->{$key}).
    		'</'.$this->{$key}->data('menuTag').'>';
    	//$this->{$key} = false; //we remove the menu since it finished rendering
    	$this->notAvailable($key);
    	return $tag;
    }

    public function getKomponentsArray($key)
    {
    	return [ 
	    	'kompoinfo' => KompoInfo::getFromElement($this->{$key}),
	    	'komponents' => $this->{$key}->komponents 
	    ];
    }

	protected function getMenuHtmlAttributes($menu)
	{
		return $this->getClassAttribute($menu).
			$this->getIdAttribute($menu).
			$this->getStyleAttribute($menu);
	}

	protected function getClassAttribute($menu)
	{
		return ' class="'.$menu->data('menuClass').
			($menu->class() ? (' '.$menu->class()) : '').'"';
	}

	protected function getIdAttribute($menu)
	{
		return $menu->id ? (' id="'.$menu->id.'"') : '';
	}

	protected function getStyleAttribute($menu)
	{
		return $menu->style ? (' style="'.$menu->style.'"') : '';
	}

	protected function getOpenContainer($menu)
	{
		return $this->hasContainer($menu) ? 
			('<div class="'.$menu->data('menuClass').' '.$menu->containerClass.'">') : '';
	}

	protected function getCloseContainer($menu)
	{
		return $this->hasContainer($menu) ? '</div>' : '';
	}

	protected function hasContainer($menu)
	{
		return property_exists($menu, 'containerClass') ? $menu->containerClass : false;
	}
}