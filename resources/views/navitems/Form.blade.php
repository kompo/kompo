<div class="vl-nav-form vl-nav-item {{-- $component->class() commented because will be on Form --}} {{ $component->data('active') }}"
	@include('kompo::partials.IdStyle')>
	{!! $component->render() !!}
</div>