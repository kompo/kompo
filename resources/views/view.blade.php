@extends($layout)

{{--
@if($object->hasMetaTags())
	
	@section('metaTags')

		@if($object->metaTags('title'))
			<title>{{ $object->metaTags('title') }}</title>
		@endif

		@if($object->metaTags('description'))
			<meta name="description" content="{{ $object->metaTags('description') }}">
		@endif

		@if($object->metaTags('keywords'))
			<meta name="keywords" content="{{ $object->metaTags('keywords') }}">
		@endif

	@endsection

@endif

--}}

@section($section)
	<div class="container">
		{{ $object }}
	</div>
@endsection
