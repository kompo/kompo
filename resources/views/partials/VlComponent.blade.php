<?php 
	$vueComponent = 'vl-'.str_replace('_', '-', Kompo\Utilities\Str::snake($component->component));
?>

<{{$vueComponent}} :vcomponent="{{$component}}"	kompoid="{{$kompoid}}"></{{$vueComponent}}>