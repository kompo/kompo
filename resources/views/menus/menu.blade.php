@if($_kompo->has($menuKey)) 

{!! $_kompo->getOpenTag($menuKey) !!}

    {{-- @include('kompo::menus.elements', $_kompo->getElementsArray($menuKey)) --}}

{!! $_kompo->getCloseTag($menuKey) !!}

@endif