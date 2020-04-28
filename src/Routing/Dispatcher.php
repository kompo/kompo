<?php

namespace Kompo\Routing;

use Kompo\Query;
use Kompo\Core\KompoInfo;
use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Form;
use Kompo\Komposers\KomposerHandler;
use Kompo\Menu;

class Dispatcher
{
    protected $komposerClass;

    protected $type;

    public $booter;

    protected $bootInfo;

    public function __construct($komposerClass = null)
    {
        if(!$komposerClass)
            $this->bootInfo = KompoInfo::getKompo();

        $this->komposerClass = $komposerClass ?: $this->bootInfo['kompoClass'];

        $this->type = static::getKomposerType($this->komposerClass);

        $this->booter = 'Kompo\\Komposers\\'.$this->type.'\\'.$this->type.'Booter';
    }
    
    public static function dispatchConnection()
    {
        return KomposerHandler::performAction(static::bootKomposerForAction());
    }

    public static function bootKomposerForAction()
    {
        $dispatcher = new static();
        $booter = $dispatcher->booter;

        return $booter::bootForAction($dispatcher->bootInfo);
    }

    public function bootFromRoute() 
    {
        $booter = $this->booter;

        if($this->type == 'Form'){
            return $booter::bootForDisplay(
                $this->komposerClass, 
                request('id'), 
                request()->except('id')
            );
        }else{
            return $booter::bootForDisplay(
                $this->komposerClass, 
                request()->all()
            );
        }
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