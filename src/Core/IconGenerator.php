<?php

namespace Kompo\Core;

use Kompo\Komponents\Komponent;
use Kompo\Svg;

class IconGenerator
{
    /**
     * Generates the icon's HTML if needed.
     *
     * @param string|Komponent $icon The icon class, html or Komponent
     *
     * @return string (The well formed icon HTML)
     */
    public static function toHtml($icon)
    {
        if (!$icon) {
            return;
        }

        if ($icon instanceof Svg) {
            return static::makeSvgIcon($icon)->__toHtml();
        }

        if ($icon instanceof Komponent) {
            return $icon->__toHtml();
        }

        return static::getFromSvgLibrary($icon) ?: (
            strip_tags($icon) === $icon ?

                    _I()->class($icon)->__toHtml() : //$icon is a class only, for ex using fontawesome

                    $icon //$icon is fully formed Html
        );
    }

    /**
     * { function_description }.
     *
     * @param <type>      $iconName    The icon name
     * @param string      $class       The class
     * @param bool|string $strokeWidth The stroke width
     *
     * @return string ( description_of_the_return_value )
     */
    public static function getFromSvgLibrary($iconString)
    {
        if (!Svg::isInLibrary($iconString)) {
            return;
        }

        return static::makeSvgIcon(_Svg($iconString))->__toHtml();
    }

    protected static function makeSvgIcon($svg)
    {
        return $svg->class('icon-svg');
    }
}
