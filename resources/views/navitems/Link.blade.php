@if(count($component->interactions))

	<vl-link class="vl-nav-item {{ $component->class() }} {{ $component->config('active') }}"
		@include('kompo::partials.IdStyle')
		:vkompo="{{ $component }}" ></vl-link>

@else

	<a class="vl-nav-item {{ $component->class() }} {{ $component->config('active') }}" 
		@include('kompo::partials.IdStyle')
		@include('kompo::partials.HrefTarget')>

		@include('kompo::partials.ItemContent', [
			'component' => $component
		])
			
	</a>

@endif