@if($component->hasTriggers())

	<vl-link class="vl-nav-item {{ $component->class() }} {{ $component->data('active') }}"
		@include('kompo::partials.IdStyle')
		:vcomponent="{{ $component }}" >

		@include('kompo::partials.ItemContent', [
			'component' => $component
		])
			
	</vl-link>

@else

	<a class="vl-nav-item {{ $component->class() }} {{ $component->data('active') }}" 
		@include('kompo::partials.IdStyle')
		@include('kompo::partials.HrefTarget')>

		@include('kompo::partials.ItemContent', [
			'component' => $component
		])
			
	</a>

@endif