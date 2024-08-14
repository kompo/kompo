<?php

namespace Kompo\Komponents\Form;

use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;

abstract class KompoModelFacade extends Facade
{
    abstract protected static function getModelBindKey();

    protected static function getFacadeAccessor()
    {
        $bindKey = static::getModelBindKey();

        if(!(static::resolveFacadeInstance($bindKey) instanceof Model)) {
            throw new \Exception("The model bind key must be an instance of Illuminate\Database\Eloquent\Model");
        }

        return $bindKey;  
    }

    public static function getClass()
    {
        return static::getFacadeRoot()::class;
    }
}