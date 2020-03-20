<?php

use Kompo\Routing\Router;

Route::middleware('web')->post('_kompo', function(){
	return Router::dispatchConnection(request());
})->name('_kompo');