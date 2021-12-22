<div class="vlFlex {{ $component->config('justifyClass') }} {{ $component->config('alignClass') }} 
	{{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

    @include('kompo::menus.elements', [ 'elements' => $component->elements ])

</div>