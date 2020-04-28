<?php 
	$vueComponent = Kompo\Core\Util::vueComponent($component);
?>

<{{$vueComponent}} :vkompo="{{$component}}"	kompoinfo="{{$kompoinfo}}"></{{$vueComponent}}>