@extends($layout)

@if($metaTags)
	
	@section('metaTags')

		@if($metaTags['title'] ?? false)
			<title>{{ $metaTags['title'] }}</title>
		@endif

		@if($metaTags['description'] ?? false)
			<meta name="description" content="{{ $metaTags['description'] }}">
		@endif

		@if($metaTags['keywords'] ?? false)
			<meta name="keywords" content="{{ $metaTags['keywords'] }}">
		@endif

	@endsection

@endif

@section($section)
	<div class="container">
		{!! $vueComponent !!}
	</div>
@endsection
