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