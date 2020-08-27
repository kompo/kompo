@if($icon = $component->data('icon'))
	{!! $icon !!}&nbsp;
@endif

@if($component->label)
	<span class="{{ $spanClass ?? '' }}">
		{!! $component->label !!}
	</span>
@endif

@if($rIcon = $component->data('rIcon'))
	&nbsp;{!! $rIcon !!}
@endif