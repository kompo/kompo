<?php

use Kompo\Http\Controllers\DispatchController;
use Kompo\Http\Controllers\LocaleController;

Route::middleware('web')->group(function () {
    Route::get('_kompo', DispatchController::class)->name('_kompo');
    Route::post('_kompo', DispatchController::class)->name('_kompo.post');
    Route::put('_kompo', DispatchController::class)->name('_kompo.put');
    Route::delete('_kompo', DispatchController::class)->name('_kompo.delete');

    if (count(config('kompo.locales'))) {
        Route::get('set-locale/{locale?}', LocaleController::class)->name('setLocale');
    }
});
