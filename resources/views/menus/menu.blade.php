@if($_kompo->has($menuKey)) 

{!! $_kompo->getOpenTag($menuKey) !!}

    {{-- @include('kompo::menus.komponents', $_kompo->getKomponentsArray($menuKey)) --}}

{!! $_kompo->getCloseTag($menuKey) !!}

@endif