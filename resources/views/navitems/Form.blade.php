<div class="vl-nav-form vl-nav-item {{ $component->class() }} {{ $component->data('active') }}"
	@include('kompo::partials.IdStyle')>
	{!! $component->render() !!}
</div>