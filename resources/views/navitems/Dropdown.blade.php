<vl-dropdown class="vl-nav-item" :vkompo="{{ $component }}">
    
    <a @include('kompo::partials.HrefTarget')>

	    @include('kompo::partials.ItemContent', ['component' => $component])
	    
	</a>

    @if(!$component->data('noCaret'))
        <i class="icon-down"></i>
    @endif

    <template v-slot:komponents>
        
        @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
    
    </template>

</vl-dropdown>