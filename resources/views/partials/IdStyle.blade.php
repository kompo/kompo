@if($component->id)
id="{{ $component->id }}" 
@endif
@if($component->style)
style="{{ $component->style }}"
@endif
@if($component->data('attrs'))
	@foreach($component->data('attrs') as $key => $value)
		{{$key}}="{{$value}}"
	@endforeach
@endif