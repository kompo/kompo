@if($icon = $component->config('icon'))
	{!! $icon !!}{!! $component->label ? '&nbsp;' : '' !!}
@endif

@if($component->label)
	<span class="{{ $spanClass ?? '' }}">
		{!! $component->label !!}
	</span>
@endif

@if($rIcon = $component->config('rIcon'))
	{!! $component->label ? '&nbsp;' : '' !!}{!! $rIcon !!}
@endif