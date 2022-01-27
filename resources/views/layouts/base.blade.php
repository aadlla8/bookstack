<!DOCTYPE html>
<html lang="{{ config('app.lang') }}" dir="{{ config('app.rtl') ? 'rtl' : 'ltr' }}"
    class="{{ setting()->getForCurrentUser('dark-mode-enabled') ? 'dark-mode ' : '' }}@yield('body-class')">

<head>
    <title>{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ setting('app-name') }}</title>

    <!-- Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta charset="utf-8">

    <!-- Social Cards Meta -->
    <meta property="og:title" content="{{ isset($pageTitle) ? $pageTitle . ' | ' : '' }}{{ setting('app-name') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @stack('social-meta')

    <!-- Styles and Fonts -->
    <link rel="stylesheet" href="{{ versioned_asset('dist/styles.css') }}">
    <link rel="stylesheet" media="print" href="{{ versioned_asset('dist/print-styles.css') }}">
    <!-- SmartMenus core CSS (required) -->
    <link href="/css/sm-core-css.css" rel="stylesheet" type="text/css" />
    <!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
    <link href="/css/sm-mint/sm-mint.css" rel="stylesheet" type="text/css" />
    @yield('head')

    <!-- Custom Styles & Head Content -->
    @include('common.custom-styles')
    @include('common.custom-head')

    @stack('head')

    <!-- Translations for JS -->
    @stack('translations')
</head>

<body class="@yield('body-class')">

    @include('common.skip-to-content')
    @include('common.notifications')
    @include('common.header')

    <div id="content" components="@yield('content-components')" class="block">
        @yield('content')
    </div>

    @include('common.footer')

    <div back-to-top class="primary-background print-hidden">
        <div class="inner">
            @icon('chevron-up') <span>{{ trans('common.back_to_top') }}</span>
        </div>
    </div>

    @yield('bottom')
    <script src="{{ versioned_asset('dist/app.js') }}" nonce="{{ $cspNonce }}"></script>
    <!-- jQuery -->

    <script src="{{ url('/libs/jquery-3.5.1.js') }}" nonce="{{ $cspNonce }}"></script>

    <!-- SmartMenus jQuery plugin -->
    <script src="{{ url('/libs/jquery.smartmenus.js') }}" nonce="{{ $cspNonce }}"></script>
    <!-- SmartMenus jQuery init -->
    <script type="text/javascript" nonce="{{ $cspNonce }}">
        $(function() {
            $('#main-menu').smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });
        });
    </script>

    @yield('scripts')



</body>

</html>
