<div class="vlFlex {{ $component->config('justifyClass') }} {{ $component->config('alignClass') }} 
	{{ $component->class() }}"
	@include('kompo::partials.IdStyle')>

    @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])

</div>