<!-- Scripts -->
@if(isset($VlScripts))
	
	@foreach( (is_array($VlScripts) ? $VlScripts : [$VlScripts]) as $k => $script)
		<script id="vl-js-{{ $k + 1 }}" src="{{ $script }}"></script>
	@endforeach

@else
	
	@if(file_exists(public_path('mix-manifest.json')))
	@foreach(json_decode(file_get_contents(public_path('mix-manifest.json')), true) as $path => $manifest)

		@if(substr($path, -3) == '.js')

			<script src="{{ mix($path) }}"></script>

		@endif

	@endforeach
	@endif

@endif

<!-- additional global custom scripts included if available -->
@includeIf('vuravel.scripts')

<!-- vuravel internal scripts -->
@include('kompo::layout-scripts')
@include('kompo::keep-session-active-script')

<!-- additional local custom stack of scripts -->
@stack('scripts')