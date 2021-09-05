<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<!-- IE compatibility - prefer edge -->
<meta http-equiv="x-ua-compatible" content="IE=edge">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Styles -->
@if(isset($VlStyles))
	
	@foreach( (is_array($VlStyles) ? $VlStyles : [$VlStyles]) as $k => $style)
		<link id="vl-css-{{ $k + 1 }}" href="{{ $style }}" rel="stylesheet">
	@endforeach

@else

	@if(file_exists(public_path('mix-manifest.json')))
	@foreach(json_decode(file_get_contents(public_path('mix-manifest.json')), true) as $path => $manifest)

		@if(substr($path, -4) == '.css')

			<link href="{{ mix($path) }}" rel="stylesheet">

		@endif

	@endforeach
	@endif

@endif

<!-- additional global custom styles included if available -->
@includeIf('kompo.styles')

<!-- Head -->
@stack('head')
@stack('header') {{-- Deprecated --}}

@hasSection('metaTags')
	@yield('metaTags')
@else
	<title>{{ config('app.name', 'Laravel') }}</title>
@endif

@include('kompo::favicon')
