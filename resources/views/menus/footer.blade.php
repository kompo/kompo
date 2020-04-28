@if($Footer)
<footer class="vl-footer {{ $Footer->class() }}" 
	@include('kompo::partials.IdStyle', [ 'component' => $Footer ])>

    @include('kompo::menus.komponents', [ 
    	'kompoinfo' => Kompo\Core\KompoInfo::getFromElement($Footer),
    	'komponents' => $Footer->komponents 
    ])

</footer>
@endif