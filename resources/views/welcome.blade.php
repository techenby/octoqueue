<x-app-layout>
    <div class="absolute top-0 right-0 z-10 p-4">
        <x-jet-secondary-button href="/login">Login</x-jet-secondary-button>
    </div>
    <div class="pb-8 bg-white dark:bg-gray-900 sm:pb-12 lg:pb-12">
        <div class="pt-8 overflow-hidden sm:pt-12 lg:relative lg:py-48">
            <div class="max-w-md px-4 mx-auto sm:max-w-3xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-2 lg:gap-24 lg:px-8">
                <div>
                    <div>
                        <x-jet-application-logo class="w-auto h-12 text-gray-900 dark:text-gray-200" />
                    </div>
                    <div class="mt-20">
                        <div class="mt-6 sm:max-w-xl">
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-200 sm:text-5xl">
                                <span class="rounded bg-indigo-50 dark:bg-indigo-900 px-2.5 py-1 text-sm font-semibold text-indigo-600 dark:text-indigo-100">Coming soon</span>
                                <span class="block">Printer management for growing farm</span>
                            </h1>
                            <p class="mt-6 text-xl text-gray-500 dark:text-gray-400">Tapping into the power of OctoPrint, manage what gets printed when, manage filaments and more.</p>
                        </div>
                        <div class="mt-12">
                            <p class="mb-2 text-base font-medium text-gray-900 dark:text-gray-200">Sign up to get notified when it launches.</p>
                            @livewire('newsletter')
                        </div>
                    </div>
                </div>
            </div>

            <div class="sm:mx-auto sm:max-w-3xl sm:px-6">
                <div class="py-12 sm:relative sm:mt-12 sm:py-16 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                    <div class="hidden sm:block">
                        <div class="absolute inset-y-0 w-screen left-1/2 rounded-l-3xl bg-gray-50 dark:bg-gray-850 lg:left-80 lg:right-0 lg:w-full"></div>
                        <svg class="absolute -mr-3 top-8 right-1/2 lg:left-0 lg:m-0" width="404" height="392" fill="none" viewBox="0 0 404 392">
                            <defs>
                                <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="4" height="4" class="text-gray-200 dark:text-gray-700" fill="currentColor" />
                                </pattern>
                            </defs>
                            <rect width="404" height="392" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" />
                        </svg>
                    </div>
                    <div class="relative pl-4 -mr-40 sm:mx-auto sm:max-w-3xl sm:px-0 lg:h-full lg:max-w-none lg:pl-12">
                        <img class="hidden w-full rounded-md shadow-xl shadow-gray-700/25 dark:block ring-1 ring-white ring-opacity-5 lg:h-full lg:w-auto lg:max-w-none" src="{{ asset('images/dashboard-dark.webp') }}" alt="Dashboard of the OctoQueue interface. There are panels showing what is currently printing, what is queued, printers that have connection issues, printers on standby, printers with no material assigned to them as well as a webcam view of the what is printing.">
                        <img class="w-full rounded-md shadow-xl dark:hidden ring-1 ring-black ring-opacity-5 lg:h-full lg:w-auto lg:max-w-none" src="{{ asset('images/dashboard-light.webp') }}" alt="Dashboard of the OctoQueue interface. There are panels showing what is currently printing, what is queued, printers that have connection issues, printers on standby, printers with no material assigned to them as well as a webcam view of the what is printing.">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
