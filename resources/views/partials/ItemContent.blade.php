@if($icon = $component->data('icon'))
	<i class="{{$icon}}"></i>&nbsp;
@endif

@if($component->label)
	<span class="{{ $spanClass ?? '' }}">
		{!! $component->label !!}
	</span>
@endif
