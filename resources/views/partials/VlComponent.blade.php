<?php 
	$vueComponent = Kompo\Core\Util::vueComponent($component);
?>

<{{$vueComponent}} :vkompo="{{$component}}"	kompoinfo="{{$kompoinfo}}" kompoid="{{$kompoid}}"></{{$vueComponent}}>