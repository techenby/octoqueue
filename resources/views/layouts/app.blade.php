<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg">
    <link rel="icon" type="image/png" href="/favicon/favicon.png">

    <!-- Styles -->
    @stack('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles

    @production
    <script src="https://sky-seven.octoqueue.com/script.js" data-site="HHGDRTTQ" defer></script>
    @endproduction
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')
</head>

<body class="h-full font-sans antialiased">
    @livewire('notifications')
    <x-jet-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @isset ($header)
            <div class="px-4 mx-auto max-w-7xl lg:px-8">
                <header class="flex items-center justify-between h-16 px-4 bg-white shadow rounded-b-md dark:bg-gray-800 dark:border dark:border-t-0 dark:border-gray-700">
                    <h1 class="text-3xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ $header }}</h1>

                    @isset($action)
                    {{ $action }}
                    @endif
                </header>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            <div class="px-4 py-10 mx-auto max-w-7xl lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewire('bit.pip')
    @stack('modals')

    @livewireScripts
</body>

</html>
