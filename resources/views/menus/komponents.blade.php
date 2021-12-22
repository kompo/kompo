@foreach($elements as $element)
	@include('kompo::navitems.'.$element->bladeComponent, [
		'component' => $element,
		'kompoinfo' => $kompoinfo,
		'kompoid' => $kompoid,
	])
@endforeach