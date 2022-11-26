<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="theme-color" content="#ffffff">

        <script src="https://sky-seven.octoqueue.com/script.js" data-site="HHGDRTTQ" defer></script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
        @stack('scripts')

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <x-jet-banner />

        @auth
        @livewire('navigation-menu')
        @endauth

        @isset($header)
        <!-- Page Heading -->
        {{ $header }}
        @endif

        {{ $slot }}

        @livewire('bit.pip')
        @livewire('notifications')

        @stack('modals')

        <x-impersonate::banner />
        @livewireScripts
    </body>
</html>
