<x-app-layout>
    <div class="bg-white isolate">
        <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]">
            <svg class="relative left-[calc(50%-11rem)] -z-10 h-[21.1875rem] max-w-none -translate-x-1/2 rotate-[30deg] sm:left-[calc(50%-30rem)] sm:h-[42.375rem]" viewBox="0 0 1155 678" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="url(#45de2b6b-92d5-4d68-a6a0-9b9b2abad533)" fill-opacity=".3" d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634 204.172-286.402c1.307 132.337 45.083 346.658 209.733 145.248C936.936 126.058 882.053-94.234 1031.02 41.331c119.18 108.451 130.68 295.337 121.53 375.223L855 299l21.173 362.054-558.954-142.079z" />
                <defs>
                    <linearGradient id="45de2b6b-92d5-4d68-a6a0-9b9b2abad533" x1="1155.49" x2="-78.208" y1=".177" y2="474.645" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#9089FC"></stop>
                        <stop offset="1" stop-color="#FF80B5"></stop>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="px-6 pt-6 lg:px-8">
            <div>
                <nav class="flex items-center justify-between h-9" aria-label="Global">
                    <div class="flex lg:min-w-0 lg:flex-1" aria-label="Global">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">OctoQueue</span>
                            <img class="h-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
                        </a>
                    </div>
                    <div class="flex lg:hidden">
                        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                            <span class="sr-only">Open main menu</span>
                            <!-- Heroicon name: outline/bars-3 -->
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                    <div class="hidden lg:flex lg:min-w-0 lg:flex-1 lg:justify-center lg:gap-x-12">
                        <a href="#features" class="font-semibold text-gray-900 hover:text-gray-900">Features</a>

                        <a href="#pricing" class="font-semibold text-gray-900 hover:text-gray-900">Pricing</a>

                        <a href="#company" class="font-semibold text-gray-900 hover:text-gray-900">Company</a>
                    </div>
                    <div class="hidden lg:flex lg:min-w-0 lg:flex-1 lg:justify-end">
                        <a href="/app/login" class="inline-block rounded-lg px-3 py-1.5 text-sm font-semibold leading-6 text-gray-900 shadow-sm ring-1 ring-gray-900/10 hover:ring-gray-900/20">Log in</a>
                    </div>
                </nav>
                <!-- Mobile menu, show/hide based on menu open state. -->
                <div role="dialog" aria-modal="true">
                    <div focus="true" class="fixed inset-0 z-10 px-6 py-6 overflow-y-auto bg-white lg:hidden">
                        <div class="flex items-center justify-between h-9">
                            <div class="flex">
                                <a href="#" class="-m-1.5 p-1.5">
                                    <span class="sr-only">OctoQueue</span>
                                    <img class="h-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
                                </a>
                            </div>
                            <div class="flex">
                                <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                                    <span class="sr-only">Close menu</span>
                                    <!-- Heroicon name: outline/x-mark -->
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flow-root mt-6">
                            <div class="-my-6 divide-y divide-gray-500/10">
                                <div class="py-6 space-y-2">
                                    <a href="#features" class="block px-3 py-2 -mx-3 text-base font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-400/10">Features</a>

                                    <a href="#pricing" class="block px-3 py-2 -mx-3 text-base font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-400/10">Marketplace</a>

                                    <a href="#company" class="block px-3 py-2 -mx-3 text-base font-semibold leading-7 text-gray-900 rounded-lg hover:bg-gray-400/10">Company</a>
                                </div>
                                <div class="py-6">
                                    <a href="/app/login" class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-gray-900 hover:bg-gray-400/10">Log in</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main>
            @include('marketing.hero')
            @include('marketing.features')
            @include('marketing.pricing')
        </main>
    </div>


</x-app-layout>
