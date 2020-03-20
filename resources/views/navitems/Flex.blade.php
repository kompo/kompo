<div class="vlFlex {{ $component->data('justifyClass') }} {{ $component->data('alignClass') }} 
	{{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

    @include('kompo::menus.components', [ 'components' => $component->components ])

</div>