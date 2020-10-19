<?php

use Kompo\Routing\Dispatcher;

Route::middleware('web')->get('_kompo', function(){
	return Dispatcher::dispatchConnection();
})->name('_kompo');

Route::middleware('web')->post('_kompo', function(){
	return Dispatcher::dispatchConnection();
})->name('_kompo.post');

Route::middleware('web')->put('_kompo', function(){
	return Dispatcher::dispatchConnection();
})->name('_kompo.put');

Route::middleware('web')->delete('_kompo', function(){
	return Dispatcher::dispatchConnection();
})->name('_kompo.delete');


Route::middleware('web')->group(function(){

	/*
	Route::get('kompo/keep-alive', function(){
		return response(null, 204);
	})->name('kompo.keep-alive');*/

	if(count(config('kompo.locales')))

		Route::get('set-locale/{locale?}', function ( $locale = 'en' ) {
		    
		    if(!array_key_exists($locale, config('kompo.locales') )) 
		    	$locale = config('app.locale');

		    \Cookie::queue('locale', $locale);
		    
		    return redirect()->back();
		
		})->name('setLocale');

});
