<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<?php 

    $_kompo = new Kompo\Core\KompoLayout(
        $Navbar ?? false,
        $LeftSidebar ?? false,
        $RightSidebar ?? false,
        $Footer ?? false,
        [
            'mainClass' => $mainClass ?? '',
            'mainStyle' => $mainStyle ?? '',
        ]
    );

    $savedCloseTag = $_kompo->wrapperCloseTag(); //cuz by the time it gets there, things change
?>

<head>
    @include('kompo::header')
</head>

<body @isset($bodyClass) class="{{$bodyClass}}" @endif>
    <div id="vl-md" class="vlBlock vlHiddenLg"></div>

    {!! $_kompo->wrapperOpenTag(config('kompo.vue_app_id')) !!}

        @if($_kompo->getPrimaryMenu())

            @include('kompo::layout', $_kompo->getLayoutKey())

        @else

            @yield('content')

        @endif

        <vl-floating-elements></vl-floating-elements>

    {!! $savedCloseTag !!}

    @include('kompo::scripts')

</body>
</html>
