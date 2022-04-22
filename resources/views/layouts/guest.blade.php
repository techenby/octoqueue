<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg">
        <link rel="icon" type="image/png" href="/favicon/favicon.png">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @production
        <script src="https://sky-seven.octoqueue.com/script.js" data-site="HHGDRTTQ" defer></script>
        @endproduction

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">
        <div class="font-sans antialiased text-gray-900 dark:text-gray-200">
            {{ $slot }}
        </div>
    </body>
</html>
