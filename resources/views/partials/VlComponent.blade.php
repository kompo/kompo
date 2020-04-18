<?php 
	$vueComponent = Kompo\Core\Util::vueComponent($component);
?>

<{{$vueComponent}} :vkompo="{{$component}}"	kompoid="{{$kompoid}}"></{{$vueComponent}}>