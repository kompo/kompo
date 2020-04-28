<?php

namespace Kompo\Core;

class KompoId extends KompoData
{
    protected static $kompoDataKey = 'kompoId';

    public static function setForKomponent($el, $label = null)
    {
        return static::setOnElement( $el, 
            ($label ? preg_replace("/[^a-zA-Z]+/", "", strip_tags($label)) : class_basename($el)).uniqid() 
        );
    }

    public static function setForKomposer($el, $kompoId = null)
    {
        $bestKompoId = $kompoId ?: class_basename($el).uniqid();
        
        return static::setOnElement( $el, $bestKompoId);
    }

    public static function appendToElement($el, $append)
    {
        return static::setOnElement( $el, static::getFromElement($el).$append );
    }

}