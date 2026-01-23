<?php

namespace Kompo\Core;

class KompoId extends KompoAjax
{
    public static $key = 'X-Kompo-Id';

    public static function setForElement($el, $label = null)
    {
        return static::setForBaseElement(
            $el,
            ($label ? preg_replace('/[^a-zA-Z]+/', '', strip_tags($label)) : class_basename($el)).uniqid()
        );
    }

    /**
     * Set a stable ID for an element based on parent and position.
     * Falls back to explicit id if set.
     */
    public static function setStableIdForElement($el, $parentId, $index)
    {
        // Priority: explicit id > position-based stable id
        if ($el->id) {
            return static::setForBaseElement($el, $el->id);
        }

        $stableId = $parentId . '-' . $index . '-' . class_basename($el);
        return static::setForBaseElement($el, $stableId);
    }

    public static function setForKomponent($el, $kompoId = null)
    {
        $kompoId = is_array($kompoId) ? ($kompoId[static::$key] ?? null) : $kompoId; //array is when coming from bootInfo

        //if null, set from id or if already set
        $bestKompoId = $kompoId ?: (static::getFromElement($el) ?: ($el->id ?: class_basename($el).uniqid()));

        return static::setForBaseElement($el, $bestKompoId);
    }

    public static function appendToElement($el, $append)
    {
        return static::setForBaseElement($el, static::getFromElement($el).$append);
    }
}
