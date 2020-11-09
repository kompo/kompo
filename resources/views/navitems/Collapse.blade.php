<vl-collapse class="vl-nav-item" :vkompo="{{ $component }}">

    <a @include('kompo::partials.HrefTarget') >

        @include('kompo::partials.ItemContent', ['component' => $component])
    
    </a>
    @if(count($component->komponents))

        <i class="icon-down-dir"></i>

    @endif
    
    <template v-slot:komponents>
        
        @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
    
    </template>

</vl-collapse>