<div class="vl-dropdown vl-nav-item {{ $component->class() }} {{ $component->data('active') }}"
    @include('kompo::partials.IdStyle')>
    
    <a @include('kompo::partials.HrefTarget')
    	onclick="toggleMenu(this)"
        class="flex items-center">

	    @include('kompo::partials.ItemContent', [
			'component' => $component
			])
	    &nbsp;<i class="icon-down"></i>
	    
	</a>

    <div class="vl-dropdown-menu {{ $component->data('vl-dropdown-menu-right') }} 
    	 {{ ($component->data('expandByDefault') || 
        ($component->data('expandIfActive') && $component->data('active') )) ? '' : 'vl-menu-closed' }}">
        
        @include('kompo::menus.components', [ 'components' => $component->submenu ])
    
    </div>

</div>