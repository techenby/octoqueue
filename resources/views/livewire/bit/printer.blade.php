<div class="overflow-hidden bg-white rounded-md shadow dark:bg-gray-800 dark:border dark:border-gray-700">
    <div class="flex items-center justify-between px-4 py-5 space-x-4 sm:px-6">
        <div class="flex items-center space-x-2">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $printer->name }}</h3>
            <span class="text-sm text-gray-700 dark:text-gray-400">({{ $printer->status }})</span>
            @if($printer->spool_id)
            <x-icon-filament class="w-8 h-8" />
            @endif
        </div>
    </div>
    <div class="relative min-h-96">
        <div class="absolute right-0 z-10 space-y-2 bottom-2">
            <div class="flex items-center p-2 space-x-4 text-gray-700 bg-red-200 shadow dark:bg-red-800 dark:text-gray-400 rounded-l-md">
                <x-icon-hotend class="w-6 h-6" />
                <span class="text-sm text-gray-900 dark:text-gray-200">205℃ / 205℃</span>
            </div>
            <div class="flex items-center p-2 space-x-4 text-gray-700 bg-blue-200 shadow dark:bg-blue-800 dark:text-gray-400 rounded-l-md">
                <x-icon-printer class="w-6 h-6" />
                <span class="text-sm text-gray-900 dark:text-gray-200">54.82℃ / 55℃</span>
            </div>
        </div>
        <img id="{{ $printer->id }}-webcam'" src="{{ $printer->webcam }}" />
    </div>
</div>
