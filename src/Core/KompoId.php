<?php

namespace Kompo\Core;

class KompoId extends KompoAjax
{
    public static $key = 'X-Kompo-Id';

    public static function setForKomponent($el, $label = null)
    {
        return static::setOnElement( $el, 
            ($label ? preg_replace("/[^a-zA-Z]+/", "", strip_tags($label)) : class_basename($el)).uniqid() 
        );
    }

    public static function setForKomposer($el, $kompoId = null)
    {
        $kompoId = is_array($kompoId) ? ($kompoId[static::$key] ?? null) : $kompoId; //array is when coming from bootInfo

        $bestKompoId = $kompoId ?: (static::getFromElement($el) ?: class_basename($el).uniqid()); //if null, set from id or if already set
        
        return static::setOnElement( $el, $bestKompoId);
    }

    public static function appendToElement($el, $append)
    {
        return static::setOnElement( $el, static::getFromElement($el).$append );
    }

}