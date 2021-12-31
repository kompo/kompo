<?php 
	$_kompo->notAvailable($menuKey);
	$savedCloseTag = $_kompo->wrapperCloseTag(); //cuz by the time it gets there, things change
?>

@if(in_array($menuKey, ['navbar', 'lsidebar', 'lsidebar|rsidebar', 'navbar|footer']))

	@include('kompo::menu', $_kompo->getFirstKey($menuKey))

@endif

{!! $_kompo->wrapperOpenTag() !!}

	@if($_kompo->getPrimaryMenu())

    	@include('kompo::layout', $_kompo->getLayoutKey())

    @else

    	@yield('content')

    @endif
    
{!! $savedCloseTag !!}

@if(in_array($menuKey, ['footer', 'rsidebar', 'lsidebar|rsidebar', 'navbar|footer']))

	@include('kompo::menu', $_kompo->getLastKey($menuKey))

@endif