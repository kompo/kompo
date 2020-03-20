@if($Navbar) 
<nav class="vl-nav {{ $Navbar->class() }}" 
	@include('kompo::partials.IdStyle', ['component' => $Navbar ])>

	@if($Navbar->containerClass ?? false)
		<div class="{{ $Navbar->containerClass }} vl-nav">
	@endif

    @include('kompo::menus.components', [ 
    	'vuravelid' => $Navbar->id,
    	'components' => $Navbar->components 
    ])

	@if($Navbar->containerClass ?? false)
		</div>
	@endif

</nav>
@endif