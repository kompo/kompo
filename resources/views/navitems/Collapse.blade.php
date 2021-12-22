<vl-collapse 
    class="vl-nav-item vlCollapse {{ $component->class() }}" 
    @include('kompo::partials.IdStyle')
    :vkompo="{{ $component }}">

    <a @include('kompo::partials.HrefTarget') >

        @include('kompo::partials.ItemContent', ['component' => $component])
    
    </a>
    
    <template v-slot:elements>
        
        @include('kompo::menus.elements', [ 'elements' => $component->elements ])
    
    </template>

</vl-collapse>