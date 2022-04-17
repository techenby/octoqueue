<x-guest-layout>
    <div class="bg-white dark:bg-gray-900">
        <main>
            <div class="fixed top-0 z-50 flex px-4 py-2 space-x-4 bg-white shadow dark:bg-gray-800 dark:border dark:border-t-0 dark:border-gray-700 rounded-b-md right-8">
                <a href="/login" class="text-blue-500 hover:underline">Login</a>
                <a href="/register" class="text-blue-500 hover:underline">Create Account</a>
            </div>

            <div class="pt-8 overflow-hidden sm:pt-12 lg:relative lg:py-48">
                <div class="max-w-md px-4 mx-auto sm:max-w-3xl sm:px-6 lg:px-8 lg:max-w-7xl lg:grid lg:grid-cols-2 lg:gap-24">
                    <div>
                        <div>
                            <x-jet-application-logo class="w-auto h-12" />
                        </div>
                        <div class="mt-20">
                            <div>
                                <span class="inline-flex items-center space-x-1 text-sm font-medium text-blue-500">
                                    <span>Just shipped version 1.0</span>
                                </span>
                            </div>
                            <div class="mt-6 sm:max-w-xl">
                                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-gray-200 sm:text-5xl">Print queue management for 3D printers</h1>
                                <p class="mt-6 text-xl text-gray-500 dark:text-gray-400">Tapping into the power of OctoPrint, manage what gets printed when, manage filaments and more.</p>
                                <a href="/login" class="mt-4 btn btn-xl btn-blue">Create an Account</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sm:mx-auto sm:max-w-3xl sm:px-6">
                    <div class="py-12 sm:relative sm:mt-12 sm:py-16 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                        <div class="hidden sm:block">
                            <div class="absolute inset-y-0 w-screen left-1/2 bg-gray-50 dark:bg-gray-800 rounded-l-3xl lg:left-80 lg:right-0 lg:w-full"></div>
                            <svg class="absolute -mr-3 top-8 right-1/2 lg:m-0 lg:left-0" width="404" height="392" fill="none" viewBox="0 0 404 392">
                                <defs>
                                    <pattern id="837c3e70-6c3a-44e6-8854-cc48c737b659" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                        <rect x="0" y="0" width="4" height="4" class="text-gray-200 dark:text-gray-600" fill="currentColor" />
                                    </pattern>
                                </defs>
                                <rect width="404" height="392" fill="url(#837c3e70-6c3a-44e6-8854-cc48c737b659)" />
                            </svg>
                        </div>
                        <div class="relative pl-4 -mr-40 sm:mx-auto sm:max-w-3xl sm:px-0 lg:max-w-none lg:h-full lg:pl-12">
                            <img class="hidden w-full rounded-md shadow-xl dark:border dark:border-gray-700 dark:block ring-1 ring-black ring-opacity-5 lg:h-full lg:w-auto lg:max-w-none" src="{{ asset('img/dashboard-screenshot-dark.png') }}" alt="">
                            <img class="w-full rounded-md shadow-xl dark:hidden ring-1 ring-black ring-opacity-5 lg:h-full lg:w-auto lg:max-w-none" src="{{ asset('img/dashboard-screenshot-light.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer section -->
        <footer class="mt-24 bg-gray-900 sm:mt-12">
            <div class="max-w-md px-4 py-12 mx-auto overflow-hidden sm:max-w-3xl sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="flex justify-center mt-8 space-x-6">
                    <a href="https://twitter.com/techenby" class="text-gray-400 hover:text-gray-300">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>

                    <a href="https://github.com/techenby/octoqueue" class="text-gray-400 hover:text-gray-300">
                        <span class="sr-only">GitHub</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                <p class="mt-8 text-base text-center text-gray-400">&copy; {{ date('Y') }} Newest Newhouse. All rights reserved.</p>
            </div>
        </footer>
    </div>
</x-guest-layout>
