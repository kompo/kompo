<?php

namespace Kompo\Routing;

use Kompo\Query;
use Kompo\Core\SessionStore;
use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Form;
use Kompo\Komposers\KomposerHandler;
use Kompo\Menu;

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

        return KomposerHandler::performAction($booter::bootForAction($dispatcher->sessionKomposer));
    }

    public function bootFromRoute() 
    {
        $booter = $this->booter;

        $modelKey = request('id');
        $store = request()->except('id'); //no store when booted from route. Use parameters instead.

        return $this->type == 'Form' ? 

            $booter::bootForDisplay($this->komposerClass, $modelKey, $store) : 

            $booter::bootForDisplay($this->komposerClass, $store);
    }

    protected static function getKomposerType($komposerClass)
    {
        if(is_a($komposerClass, Form::class, true)){
            return 'Form';
        }elseif (is_a($komposerClass, Query::class, true)) {
            return 'Query';
        }elseif (is_a($komposerClass, Menu::class, true)) {
            return 'Menu';
        }
        throw new NotBootableFromRouteException($komposerClass);
    }
}