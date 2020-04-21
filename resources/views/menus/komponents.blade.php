@foreach($komponents as $komponent)
	@include('kompo::navitems.'.$komponent->bladeComponent, [
		'component' => $komponent
	])
@endforeach