<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;

class KompoId
{
    protected static $kompoIdKey = 'kompoId';

    public static function setForKomponent($el, $label = null)
    {
        return static::setOnElement( $el, 
            ($label ? preg_replace("/[^a-zA-Z]+/", "", strip_tags($label)) : class_basename($el)).uniqid() 
        );
    }

    public static function setForKomposer($el)
    {
        $bestKompoId = Crypt::encryptString(get_class($el).uniqid());
        //old session way
        //$bestKompoId = class_basename($el).uniqid();
        return static::setOnElement( $el, $bestKompoId);
    }

    public static function get($el)
    {
        return $el->data( static::$kompoIdKey );
    }

    public static function setOnElement($el, $bestKompoId)
    {
        return $el->data([ static::$kompoIdKey => $bestKompoId ]);
    }
}