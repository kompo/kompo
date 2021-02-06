<vl-collapse 
    class="vl-nav-item vlCollapse {{ $component->class() }}" 
    @include('kompo::partials.IdStyle')
    :vkompo="{{ $component }}">

    <a @include('kompo::partials.HrefTarget') >

        @include('kompo::partials.ItemContent', ['component' => $component])
    
    </a>
    
    <template v-slot:komponents>
        
        @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
    
    </template>

</vl-collapse>