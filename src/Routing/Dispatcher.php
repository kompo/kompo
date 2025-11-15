<?php

namespace Kompo\Routing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoInfo;
use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Form;
use Kompo\Komponents\KomponentHandler;
use Kompo\Menu;
use Kompo\Query;

class Dispatcher
{
    protected $komponentClass;

    protected $type;

    protected $komponentTypeClass;

    public $booter;

    protected $bootInfo;

    public function __construct($komponentClass = null)
    {
        if (!$komponentClass) {
            $this->bootInfo = KompoInfo::getKompo();
        }

        $this->komponentClass = $komponentClass ?: $this->bootInfo['kompoClass'];

        $this->type = static::getKomponentType($this->komponentClass);

        $this->komponentTypeClass = 'Kompo\\'.$this->type;

        $this->booter = 'Kompo\\Komponents\\'.$this->type.'\\'.$this->type.'Booter';
    }

    public static function dispatchConnection()
    {
        if (KompoAction::is('refresh-many')) {
            return static::refreshManyKomponents();
        }

        if (KompoAction::is('browse-many')) {
            return static::browseManyQueries();
        }

        if (KompoAction::is('refresh-self')) {
            return static::rebootKomponentForDisplay();
        }
      
        if (KompoAction::is('delete-item')) {
            return KomponentHandler::deleteRecord();
        }

        $komponent = static::bootKomponentForAction();
        $action = KomponentHandler::performAction($komponent);

        if (method_exists($komponent, 'afterKompoAction')) {
            $action = $komponent->afterKompoAction(KompoAction::header(), $action);
        }
        
        return $action;
    }

    public static function bootKomponentForAction()
    {
        $dispatcher = new static();

        $type = $dispatcher->komponentTypeClass;

        return $type::constructFromBootInfo($dispatcher->bootInfo)->bootForAction();
    }

    public function bootKomponentForDisplay()
    {
        $type = $this->komponentTypeClass;

        if ($this->type == 'Form') {
            $komponent = $type::constructFromArray([
                'kompoClass' => $this->komponentClass, 
                'modelKey' => request('id'), 
                'store' => request()->except('id'),
            ]);
        } else {
            $komponent = $type::constructFromArray([
                'kompoClass' => $this->komponentClass, 
                'store' => request()->all(),
            ]);
        }
        
        return $komponent->bootForDisplay();
    }

    protected static function rebootKomponentForDisplay()
    {
        $d = new static();
        $type = $d->komponentTypeClass;

        if ($d->type == 'Form') {
            $komponent = $type::constructFromArray([
                'kompoClass' => $d->komponentClass, 
                'modelKey' => $d->bootInfo['modelKey'], 
                'store' => $d->bootInfo['store'],
            ]);
        } else {
            $komponent = $type::constructFromArray([
                'kompoClass' => $d->komponentClass, 
                'store' => $d->bootInfo['store'],
            ]);
        }

        return $komponent->bootForDisplay($d->bootInfo['parameters']);
    }

    protected static function refreshManyKomponents()
    {
        return static::runManyRequests('refresh-self');
    }

    protected static function browseManyQueries()
    {
        return static::runManyRequests('browse-items', [
            'X-Kompo-Page' => 'page',
            'X-Kompo-Sort' => 'sort',
        ]);
    }

    protected static function runManyRequests($baseAction, $additionalHeaders = [])
    {
        $responses = [];

        foreach (request()->all() as $sub) {
            
            $subrequest = clone request();

            $subrequest->replace(static::parseArrayParametersInRequest($sub['data'] ?? []));

            $subrequest->headers->set(KompoInfo::$key, $sub['kompoinfo']);
            $subrequest->headers->set(KompoAction::$key, $baseAction);

            foreach ($additionalHeaders as $key => $requestKey) {
                $subrequest->headers->set($key, $sub[$requestKey]);
            }

            RequestFacade::swap($subrequest);

            $responses[$sub['kompoid']] = static::dispatchConnection();
        }

        return $responses;
    }

    public static function getKomponentType($komponentClass)
    {
        if (is_a($komponentClass, Form::class, true)) {
            return 'Form';
        } elseif (is_a($komponentClass, Query::class, true)) {
            return 'Query';
        } elseif (is_a($komponentClass, Menu::class, true)) {
            return 'Menu';
        }

        throw new NotBootableFromRouteException($komponentClass);
    }

    public static function parseArrayParametersInRequest($initialRequestData)
    {
        $parsedArrayParameters = [];
        parse_str(http_build_query($initialRequestData), $parsedArrayParameters);

        return collect(array_merge($initialRequestData, $parsedArrayParameters))->filter(
            fn($v, $key) => strpos($key, '[') === false
        )->all();
    }
}
