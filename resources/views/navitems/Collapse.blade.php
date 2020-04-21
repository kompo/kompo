<?php 
    $vlCollapseOpen = $component->data('expandByDefault') || 
        ($component->data('expandIfActive') && $component->data('active') ); 
?>
<div 
    @include('kompo::partials.IdStyle') 
    class="vl-collapse vl-nav-item {{ $component->class() }} {{ $component->data('active') }}">
    
    <div class="vl-collapse-toggler {{ $vlCollapseOpen ? '' : 'vl-toggler-closed' }}" 
        onclick="toggleMenu(this)">

        <a @include('kompo::partials.HrefTarget') >

            @include('kompo::partials.ItemContent', ['component' => $component])
        
        </a>
        <div>
            <i class="icon-down-dir"></i>
        </div>
    </div>
    
    <div class="vl-collapse-menu {{ $vlCollapseOpen ? '' : 'vl-menu-closed' }}">
        
        @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
    
    </div>

</div>