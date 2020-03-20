<div class="vl-nav-html {{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

	@include('kompo::menus.components', [ 'components' => $component->components ])
	
</div>