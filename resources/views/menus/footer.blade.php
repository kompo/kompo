@if($Footer)
<footer class="vl-footer {{ $Footer->class() }}" 
	@include('kompo::partials.IdStyle', [ 'component' => $Footer ])>

    @include('kompo::menus.komponents', [ 
    	'kompoid' => Kompo\Core\KompoId::get($Footer),
    	'komponents' => $Footer->komponents 
    ])

</footer>
@endif