<vl-dropdown 
    class="vl-nav-item vlDropdown {{ $component->class() }}" 
    @include('kompo::partials.IdStyle')
    :vkompo="{{ $component }}">
    
    <a @include('kompo::partials.HrefTarget')>

	    @include('kompo::partials.ItemContent', ['component' => $component])
	    
	</a>

    @if(!$component->config('noCaret'))
        <i class="icon-down"></i>
    @endif

    <template v-slot:elements>
        
        @include('kompo::menus.elements', [ 'elements' => $component->elements ])
    
    </template>

</vl-dropdown>