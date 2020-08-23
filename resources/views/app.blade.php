<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<?php 

    $_kompo = new Kompo\Core\KompoLayout(
        $Navbar ?? false,
        $LeftSidebar ?? false,
        $RightSidebar ?? false,
        $Footer ?? false
    );

    /*TODO: remove docs & fix centering
    $VlFooterOutside = optional($Footer)->out;
    $VlCentered = ($VlHasAnySidebar || $Navbar) ? '' : (($neverCenter ?? false) ? '' : 'justify-center items-center');*/

    $savedCloseTag = $_kompo->wrapperCloseTag(); //cuz by the time it gets there, things change
?>
<head>
    @include('kompo::header')
</head>

<body>
    <div id="vl-md" class="vlBlock vlHiddenLg"></div>

    {!! $_kompo->wrapperOpenTag(config('kompo.vue_app_id')) !!}

        @if($_kompo->getPrimaryMenu())

            @include('kompo::layout', $_kompo->getLayoutKey())

        @else

            @yield('content')

        @endif

        <vl-alerts></vl-alerts>
        <vl-modal name="vlDefaultModal"></vl-modal>

    {!! $savedCloseTag !!}

    @include('kompo::scripts')

</body>
</html>
