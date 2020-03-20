<?php

namespace Kompo\Core;

class KompoIdCreator
{
    public static function setForKomponent($el, $label = null)
    {
        return static::addUniqId( $el, $label ? preg_replace("/[^a-zA-Z]+/", "", strip_tags($label)) : class_basename($el) );
    }

    public static function setForKomposer($el)
    {
        return static::addUniqId( $el, class_basename($el) );
    }

    private static function addUniqId($el, $bestKompoId)
    {
        return $el->data([ 'kompoId' => $bestKompoId.uniqid() ]);
    }
}