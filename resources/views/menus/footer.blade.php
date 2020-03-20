@if($Footer)
<footer class="vl-footer {{ $Footer->class() }}" 
	@include('kompo::partials.IdStyle', [ 'component' => $Footer ])>

    @include('kompo::menus.components', [ 
    	'vuravelid' => $Footer->id,
    	'components' => $Footer->components 
    ])

</footer>
@endif