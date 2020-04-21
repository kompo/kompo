<div class="vlFlex {{ $component->data('justifyClass') }} {{ $component->data('alignClass') }} 
	{{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

    @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])

</div>