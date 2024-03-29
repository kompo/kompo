<?php

namespace Kompo\Core;

use Kompo\Elements\Element;
use Kompo\Svg;

class IconGenerator
{
    /**
     * Generates the icon's HTML if needed.
     *
     * @param string|Element  $icon           The icon class, html or Element
     * @param string|null     $iconClass      An optional class that we may assign directly to the icon
     *
     * @return string (The well formed icon HTML)
     */
    public static function toHtml($icon, $iconClass = null)
    {
        if (!$icon) {
            return;
        }

        if ($icon instanceof Svg) {
            return static::makeSvgIcon($icon)->__toHtml();
        }

        if ($icon instanceof Element) {
            return $icon->__toHtml();
        }

        return static::getFromSvgLibrary($icon, $iconClass) ?: (
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
     *
     * @return string ( description_of_the_return_value )
     */
    public static function getFromSvgLibrary($iconString, $iconClass = null)
    {
        if (!Svg::isInLibrary($iconString)) {
            return;
        }

        return static::makeSvgIcon(_Svg($iconString)->class($iconClass ?: ''))->__toHtml();
    }

    protected static function makeSvgIcon($svg)
    {
        return $svg->class('icon-svg');
    }
}
