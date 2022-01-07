<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<?php 

    $_kompo = new Kompo\Core\KompoLayout(
        $Navbar ?? false,
        $LeftSidebar ?? false,
        $RightSidebar ?? false,
        $Footer ?? false,
        [
            'mainClass' => isset($mainClass) ? $mainClass : '',
            'mainStyle' => isset($mainStyle) ? $mainStyle : '',
        ]
    );

    $savedCloseTag = $_kompo->wrapperCloseTag(); //by the time it gets there, things change
?>

<head>
    @include('kompo::head')
</head>

<body @isset($bodyClass) class="{{$bodyClass}}" @endif>

    @includeIf('kompo.body-top')

    {!! $_kompo->wrapperOpenTag(config('kompo.vue_app_id')) !!}

        @if($_kompo->getPrimaryMenu())

            @include('kompo::layout', $_kompo->getLayoutKey())

        @else

            @includeIf('kompo.content-top')

            @yield('content')

            @includeIf('kompo.content-bottom')

        @endif

        <vl-floating-elements></vl-floating-elements>

    {!! $savedCloseTag !!}

    @includeIf('kompo.body-bottom')

    @include('kompo::scripts')

</body>
</html>
