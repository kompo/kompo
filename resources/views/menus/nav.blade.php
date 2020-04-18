@if($Navbar) 
<nav class="vl-nav {{ $Navbar->class() }}" 
	@include('kompo::partials.IdStyle', ['component' => $Navbar ])>

	@if($Navbar->containerClass ?? false)
		<div class="{{ $Navbar->containerClass }} vl-nav">
	@endif

    @include('kompo::menus.components', [ 
    	'kompoid' => Kompo\Core\KompoId::get($Navbar),
    	'components' => $Navbar->components 
    ])

	@if($Navbar->containerClass ?? false)
		</div>
	@endif

</nav>
@endif