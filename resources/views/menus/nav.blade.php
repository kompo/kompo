@if($Navbar) 
<nav class="vl-nav {{ $Navbar->class() }}" 
	@include('kompo::partials.IdStyle', ['component' => $Navbar ])>

	@if($Navbar->containerClass ?? false)
		<div class="{{ $Navbar->containerClass }} vl-nav">
	@endif

    @include('kompo::menus.komponents', [ 
    	'kompoinfo' => Kompo\Core\KompoInfo::getFromElement($Navbar),
    	'komponents' => $Navbar->komponents 
    ])

	@if($Navbar->containerClass ?? false)
		</div>
	@endif

</nav>
@endif