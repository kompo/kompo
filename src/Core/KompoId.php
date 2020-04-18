<?php

namespace Kompo\Core;

class KompoId
{
    protected static $kompoIdKey = 'kompoId';

    public static function setForKomponent($el, $label = null)
    {
        return static::addUniqId( $el, $label ? preg_replace("/[^a-zA-Z]+/", "", strip_tags($label)) : class_basename($el) );
    }

    public static function setForKomposer($el)
    {
        return static::addUniqId( $el, class_basename($el) );
    }

    public static function get($el)
    {
        return $el->data( static::$kompoIdKey );
    }

    private static function addUniqId($el, $bestKompoId)
    {
        return $el->data([ static::$kompoIdKey => $bestKompoId.uniqid() ]);
    }
}