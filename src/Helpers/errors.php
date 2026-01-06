<?php 

if (!function_exists('logKompoRequest')) {

    function logKompoRequest($message)
    {
		$currentKomponent = app()->bound('currentKomponent') ? app()->make('currentKomponent') : 'Unknown component yet';

        \Log::alert($message.' '.$currentKomponent, [
            'context' => array_merge(
                [
                    'previous_url' => url()->previous(),
                    'user_id' => auth()->user()?->id,
                    'method' => request()->method(),
                    'parameters' => collect(request()->all())->except(['password', 'password_confirmation', 'current_password'])->toArray(),
                ],
                request()->headers->all()
            ),
        ]);
    }

}