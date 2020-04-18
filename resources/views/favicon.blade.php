
@if(file_exists(public_path('favicon/'.($faviconPrefix ?? '').'favicon-32x32.png')))

{{-- New Generator: https://realfavicongenerator.net --}}
<link rel="apple-touch-icon" sizes="180x180" 
	href="{{ asset('favicon/'.($faviconPrefix ?? '').'apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" 
	href="{{ asset('favicon/'.($faviconPrefix ?? '').'favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" 
	href="{{ asset('favicon/'.($faviconPrefix ?? '').'favicon-16x16.png') }}">
<link rel="manifest" 
	href="{{ asset('favicon/'.($faviconPrefix ?? '').'site.webmanifest') }}">
<link rel="mask-icon" 
	href="{{ asset('favicon/'.($faviconPrefix ?? '').'safari-pinned-tab.svg') }}" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">


@endif