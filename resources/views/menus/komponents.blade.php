@foreach($komponents as $komponent)
	@include('kompo::navitems.'.$komponent->bladeComponent, [
		'component' => $komponent,
		'kompoinfo' => $kompoinfo,
		'kompoid' => $kompoid,
	])
@endforeach