<div class="vl-nav-html {{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

	@include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
	
</div>