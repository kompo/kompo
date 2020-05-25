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

?>

<head>
    @include('kompo::header')
</head>

<body>
    <div id="vl-mobile-indicator" class="vlBlock vlHiddenLg"></div>

    {!! $_kompo->wrapperOpenTag(config('kompo.vue_app_id')) !!}

        @include('kompo::layout', $_kompo->getLayoutKey())

        <vl-alerts></vl-alerts>
        <vl-modal name="vlDefaultModal"></vl-modal>

    </div>

    @include('kompo::scripts')

</body>
</html>
