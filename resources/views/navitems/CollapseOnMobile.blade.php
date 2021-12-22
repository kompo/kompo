<div class="vl-navbar-toggler vl-toggler-closed vl-nav-item"
    onclick="toggleMenu(this, true)"
 	aria-label="Open menu"
 	aria-expanded="false">
 	<span>{!! $component->label ?: '&#9776;' !!}</span>
 	<span class="vlHidden">&#10005;</span>
</div>
 	
<div class="vl-collapse-on-mobile-menu vl-menu-closed {{ $component->class() }}" 
	@include('kompo::partials.IdStyle')>
	
	<div class="vl-nav-left">
		@if(count($component->leftMenu))
			@include('kompo::menus.elements', [ 'elements' => $component->leftMenu ])
		@endif
	</div>
	<div class="vl-nav-right">
		@if(count($component->rightMenu))
			@include('kompo::menus.elements', [ 'elements' => $component->rightMenu ])
		@endif
	</div>

	@if(isset($_kompo) && $_kompo->hasAnySidebar())
	<div class="vlBlock vlHiddenLg">
		@if($_kompo->has('lsidebar'))
			<div id="vl-sidebar-l-mobile"></div>
		@endif
		@if($_kompo->has('rsidebar'))
			<div id="vl-sidebar-r-mobile"></div>
		@endif
	</div>
	@endif
    
</div>