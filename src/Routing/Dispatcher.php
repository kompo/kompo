<?php

namespace Kompo\Routing;

use Kompo\Form;
use Kompo\Menu;
use Kompo\Catalog;
use Kompo\Core\SessionStore;
use Kompo\Exceptions\NotBootableFromRouteException;

class Dispatcher
{
    protected $komposerClass;

    protected $type;

    public $booter;

    protected $sessionKomposer;

    public function __construct($komposerClass = null)
    {
        if(!$komposerClass)
            $this->sessionKomposer = SessionStore::getKompo();


        $this->komposerClass = $komposerClass ?: $this->sessionKomposer['kompoClass'];

        $this->type = static::getKomposerType($this->komposerClass);

        $this->booter = 'Kompo\\Komposers\\'.$this->type.'\\'.$this->type.'Booter';
    }
    
    public static function dispatchConnection()
    {
        $dispatcher = new static();
        $booter = $dispatcher->booter;

        return $booter::performAction($dispatcher->sessionKomposer);        
    }

    public function bootFromRoute() //no store when booted from route. Use parameters instead.
    {
        $booter = $this->booter;

        return $this->type == 'Form' ? $booter::bootForDisplay($this->komposerClass, request('id')) : $booter::bootForDisplay($this->komposerClass);
    }

    protected static function getKomposerType($komposerClass)
    {
        if(is_a($komposerClass, Form::class, true)){
            return 'Form';
        }elseif (is_a($komposerClass, Catalog::class, true)) {
            return 'Catalog';
        }elseif (is_a($komposerClass, Menu::class, true)) {
            return 'Menu';
        }
        throw new NotBootableFromRouteException($komposerClass);
    }
}