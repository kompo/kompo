@foreach($components as $component)
	@include('kompo::navitems.'.$component->menuComponent, [
		'component' => $component
	])
@endforeach