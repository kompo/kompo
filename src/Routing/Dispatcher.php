<?php

namespace Kompo\Routing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoInfo;
use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Form;
use Kompo\Komposers\KomposerHandler;
use Kompo\Menu;
use Kompo\Query;

class Dispatcher
{
    protected $komposerClass;

    protected $type;

    protected $komposerTypeClass;

    public $booter;

    protected $bootInfo;

    public function __construct($komposerClass = null)
    {
        if (!$komposerClass) {
            $this->bootInfo = KompoInfo::getKompo();
        }

        $this->komposerClass = $komposerClass ?: $this->bootInfo['kompoClass'];

        $this->type = static::getKomposerType($this->komposerClass);

        $this->komposerTypeClass = 'Kompo\\'.$this->type;

        $this->booter = 'Kompo\\Komposers\\'.$this->type.'\\'.$this->type.'Booter';
    }

    public static function dispatchConnection()
    {
        if (KompoAction::is('refresh-many')) {
            return static::refreshManyKomposers();
        }

        if (KompoAction::is('browse-many')) {
            return static::browseManyQueries();
        }

        if (KompoAction::is('refresh-self')) {
            return static::rebootKomposerForDisplay();
        }

        return KomposerHandler::performAction(static::bootKomposerForAction());
    }

    public static function bootKomposerForAction()
    {
        $dispatcher = new static();

        $type = $dispatcher->komposerTypeClass;

        return $type::constructFromBootInfo($dispatcher->bootInfo)->bootForAction();
    }

    public function bootKomposerForDisplay()
    {
        $type = $this->komposerTypeClass;

        if ($this->type == 'Form') {
            $komposer = $type::constructFromArray([
                'kompoClass' => $this->komposerClass, 
                'modelKey' => request('id'), 
                'store' => request()->except('id'),
            ]);
        } else {
            $komposer = $type::constructFromArray([
                'kompoClass' => $this->komposerClass, 
                'store' => request()->all(),
            ]);
        }
        
        return $komposer->bootForDisplay();
    }

    protected static function rebootKomposerForDisplay()
    {
        $d = new static();
        $type = $d->komposerTypeClass;

        if ($d->type == 'Form') {
            $komposer = $type::constructFromArray([
                'kompoClass' => $d->komposerClass, 
                'modelKey' => $d->bootInfo['modelKey'], 
                'store' => $d->bootInfo['store'],
            ]);
        } else {
            $komposer = $type::constructFromArray([
                'kompoClass' => $d->komposerClass, 
                'modelKey' => $d->bootInfo['store'],
            ]);
        }

        return $komposer->bootForDisplay($d->bootInfo['parameters']);
    }

    protected static function refreshManyKomposers()
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

    public static function getKomposerType($komposerClass)
    {
        if (is_a($komposerClass, Form::class, true)) {
            return 'Form';
        } elseif (is_a($komposerClass, Query::class, true)) {
            return 'Query';
        } elseif (is_a($komposerClass, Menu::class, true)) {
            return 'Menu';
        }

        throw new NotBootableFromRouteException($komposerClass);
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
