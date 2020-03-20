<div class="vl-nav-html {{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

	@include('kompo::partials.ItemContent', [
		'component' => $component
	])
	
</div>