<vl-collapse 
    class="vl-nav-item vlCollapse {{ $component->class() }}" 
    @include('kompo::partials.IdStyle')
    :vkompo="{{ $component }}">

    <a @include('kompo::partials.HrefTarget') >

        @include('kompo::partials.ItemContent', ['component' => $component])
    
    </a>
    @if(count($component->komponents) && !$component->config('noCaret'))

        <i class="icon-down-dir"></i>

    @endif
    
    <template v-slot:komponents>
        
        @include('kompo::menus.komponents', [ 'komponents' => $component->komponents ])
    
    </template>

</vl-collapse>