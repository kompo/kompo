@if($component->id)
id="{{ $component->id }}" 
@endif
@if($component->style)
style="{{ $component->style }}"
@endif
@if($component->config('attrs'))
	@foreach($component->config('attrs') as $key => $value)
		{{$key}}="{{$value}}"
	@endforeach
@endif