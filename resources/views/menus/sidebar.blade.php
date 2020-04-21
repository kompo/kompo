@if($Sidebar)

<div class="vlHidden vlFlexLg" id="{{ $Sidebar->data('menuType') }}-container">

	<aside @include('kompo::partials.IdStyle', ['component' => $Sidebar ])
		class="{{ $Sidebar->data('menuType') }} {{ $Sidebar->class }}">

	    @include('kompo::menus.komponents', [ 
	    	'kompoid' => Kompo\Core\KompoId::get($Sidebar),
	    	'komponents' => $Sidebar->komponents 
	    ])

	</aside>

</div>

<vl-panel class="vlHidden vlFlexLg" id="{{ $Sidebar->data('menuType') }}-panel"></vl-panel>

@endif