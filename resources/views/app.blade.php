<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<?php 
$Navbar = $Navbar ?? false;
$Footer = $Footer ?? false;
$LeftSidebar = isset($LeftSidebar) ? $LeftSidebar->data(['menuType' => 'vl-sidebar-l']) : false;
$RightSidebar = isset($RightSidebar) ? $RightSidebar->data(['menuType' => 'vl-sidebar-r']) : false;

$VlHasAnySidebar = $LeftSidebar || $RightSidebar;
$VlHasAnyTopSidebar = optional($LeftSidebar)->top || optional($RightSidebar)->top;
$VlFooterOutside = optional($Footer)->out;
$VlCentered = ($VlHasAnySidebar || $Navbar) ? '' : (($neverCenter ?? false) ? '' : 'justify-center items-center');

?>

<head>
    @include('kompo::header')
</head>

<body>
    <div id="vl-mobile-indicator" class="vlBlock vlHiddenLg"></div>

    <div id="app">

        @includeWhen(!$VlHasAnyTopSidebar, 'kompo::menus.nav')

        <vl-alerts></vl-alerts>

        <div class="vlFlex vlWFull">

            @include('kompo::menus.sidebar', ['Sidebar' => $LeftSidebar])

            <div id="vl-wrapper" class="vlWFull">
                
                @includeWhen($VlHasAnyTopSidebar, 'kompo::menus.nav')

                <main id="vl-main" class="vlFlexCol {{ $VlCentered }}">
                    
                    <vl-panel id="vl-main-panel"></vl-panel>

                    <div id="vl-content">
                        @yield('content')
                    </div>

                </main>

                @includeWhen(!$VlFooterOutside, 'kompo::menus.footer')

            </div>

            @include('kompo::menus.sidebar', ['Sidebar' => $RightSidebar])

        </div>

        @includeWhen($VlFooterOutside,'kompo::menus.footer')

        <vl-modal name="vlDefaultModal"></vl-modal>

    </div>

    @include('kompo::scripts')

</body>
</html>
