<?php

namespace Kompo\Http\Controllers;

class LocaleController
{
    public function __invoke($locale = '')
    {
        if (!array_key_exists($locale, config('kompo.locales'))) {
            $locale = config('app.locale');
        }

        \Cookie::queue('locale', $locale);

        if (auth()->user() && method_exists(auth()->user(), 'setLocalePreference')) {
        	auth()->user()->setLocalePreference($locale);
        }

        return redirect()->back();
    }
}
