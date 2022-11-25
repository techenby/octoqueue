<x-guest-layout>
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
                            <form action="#" class="sm:flex sm:w-full sm:max-w-lg">
                                <div class="flex-1 min-w-0">
                                    <label for="hero-email" class="sr-only">Email address</label>
                                    <input id="hero-email" type="email" class="block w-full px-5 py-3 text-base text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:placeholder-gray-400 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter your email">
                                </div>
                                <div class="mt-4 sm:mt-0 sm:ml-3">
                                    <button type="submit" class="block w-full px-5 py-3 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:px-10">Notify me</button>
                                </div>
                            </form>
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
                        <img class="w-full rounded-md shadow-xl ring-1 ring-black ring-opacity-5 lg:h-full lg:w-auto lg:max-w-none" src="https://tailwindui.com/img/component-images/top-nav-with-multi-column-layout-screenshot.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
