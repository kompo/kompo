<?php

use Kompo\Routing\Dispatcher;

Route::middleware('web')->post('_kompo', function(){
	return Dispatcher::dispatchConnection();
})->name('_kompo');