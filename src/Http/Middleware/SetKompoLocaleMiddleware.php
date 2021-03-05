<?php

namespace Kompo\Http\Middleware;

use App;
use Closure;
use Config;
use Cookie;
use Session;

class SetKompoLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * First, check what the user's browser default language is.
         */
        $defaultLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        /**
         * If the browser's language is not in one of the apps languages, use the default app language.
         */
        if (
            !array_key_exists($defaultLocale, config('kompo.locales')) ||
            !config('kompo.locales')
        ) {
            $defaultLocale = config('app.locale');
        }

        /*
         * If cookie has locale, use it. Otherwise, fall back to the default from above.
         */
        $locale = Cookie::get('locale', $defaultLocale);

        /*
         * We set the session locale (not the cookie). Cookie is only set when user chooses another language
         */
        session(['kompo_locale' => $locale]);
        App::setlocale($locale);

        //To delete - dead end. But code is useful...
        //Session::put('pass_to_script.translations', app('translator')->getLoader()->load($locale,'*','*') );

        return $next($request);
    }
}
