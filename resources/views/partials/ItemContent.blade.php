@if($icon = $component->config('icon'))
	{!! $icon !!}&nbsp;
@endif

@if($component->label)
	<span class="{{ $spanClass ?? '' }}">
		{!! $component->label !!}
	</span>
@endif

@if($rIcon = $component->config('rIcon'))
	&nbsp;{!! $rIcon !!}
@endif