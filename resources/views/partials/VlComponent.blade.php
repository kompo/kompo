<?php 
	$vueComponent = Kompo\Core\Util::vueComponent($component);
?>

<{{$vueComponent}} :vcomponent="{{$component}}"	kompoid="{{$kompoid}}"></{{$vueComponent}}>