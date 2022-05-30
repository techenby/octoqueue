<div class="absolute right-0 z-10 space-y-2 bottom-2">
    <div class="flex items-center p-2 space-x-4 text-gray-700 bg-red-200 shadow dark:bg-red-800 dark:text-gray-400 rounded-l-md">
        <x-icon-hotend class="w-6 h-6" />
        <span class="text-sm text-gray-900 dark:text-gray-200 {{ $hotend === 'Connecting' ? 'animate-pulse' : '' }}">{{ $hotend }}</span>
    </div>
    <div class="flex items-center p-2 space-x-4 text-gray-700 bg-blue-200 shadow dark:bg-blue-800 dark:text-gray-400 rounded-l-md">
        <x-icon-printer class="w-6 h-6" />
        <span class="text-sm text-gray-900 dark:text-gray-200 {{ $hotend === 'Connecting' ? 'animate-pulse' : '' }}">{{ $bed }}</span>
    </div>
</div>
