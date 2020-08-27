<?php

namespace Kompo\Core;

class IconGenerator
{
    /**
     * Generates the icon's HTML if needed
     *
     * @param string  $iconClassOrHtml  The icon class or html
     *
     * @return string (The well formed icon HTML)
     */
    public static function toHtml($iconString)
    {
    	return strip_tags($iconString) === $iconString ? 

                    ('<i class="'.$iconString.'"></i>') : //when the user specifies classes only, for ex using fontawesome

                    $iconString;
    }
}