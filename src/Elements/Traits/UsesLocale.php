<?php

namespace Kompo\Elements\Traits;

trait UsesLocale
{
    /**
     * Passes the selected locale to the Front-End element.
     *
     * @return self
     */
    public function passLocale()
    {
        return $this->config([
            'kompo_locale' => session('kompo_locale'),
        ]);
    }
}
