<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <script defer src="https://unpkg.com/@alpinejs/ui@3.10.5-beta.7/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/@alpinejs/focus@3.10.5/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css'])

        @stack('scripts')

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">
        {{ $slot }}

        @livewire('notifications')
        @livewireScripts
    </body>
</html>
