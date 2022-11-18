@props(['breadcrumbs' => false, 'title' => 'Add a title'])

<header class="bg-white shadow dark:bg-gray-850 dark:border-b dark:border-gray-700">
    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="space-y-2">
            @if($breadcrumbs)
            <x-layout.breadcrumbs :breadcrumbs="$breadcrumbs" />
            @endif
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="flex items-center space-x-4 text-2xl font-bold leading-7 text-gray-900 dark:text-gray-200 sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ $title }}
                    </h1>
                </div>
                <div class="flex flex-shrink-0 mt-4 md:mt-0 md:ml-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</header>
