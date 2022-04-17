<div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 dark:bg-gray-900 sm:justify-center sm:pt-0">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md dark:border dark:border-gray-700 dark:bg-gray-800 sm:max-w-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
