<div wire:init="load" class="overflow-hidden bg-white rounded-md shadow dark:bg-gray-800 dark:border dark:border-gray-700">
    <div class="flex items-center justify-between px-4 py-5 space-x-4 sm:px-6">
        <div class="flex items-center space-x-2">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $printer->name }}</h3>
            <span class="text-sm text-gray-700 dark:text-gray-400">({{ $status }})</span>
            @if ($printer->spool_id)
            <x-icon-filament class="w-8 h-8" style="fill: {{ $printer->spool->color_hex }}" />
            @endif
        </div>
        @if ($currentJob)
        <div>
            <x-jet-button type="button" wire:click="completed">Mark as Completed</x-jet-button>
        </div>
        @endif
    </div>
    <div class="relative min-h-96">
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
        <img id="{{ $printer->id }}-webcam'" src="{{ $printer->webcam }}" />
    </div>
    @if ($currentJob)
    <div class="px-4 py-5 space-y-2 text-gray-700 sm:px-6 dark:text-gray-400">
        <p>Current Job: <span class="text-gray-900 dark:text-gray-200">{{ $currentJob->name }}</span></p>
        <p>Started At: <x-date :date="$currentJob->started_at" format="m/d/y h:ia" class="text-gray-900 dark:text-gray-200" />
    </div>
    @endif
    @if ($nextJob)
    <div class="flex items-center justify-between px-4 py-5 space-y-2 text-gray-700 sm:px-6 dark:text-gray-400">
        <p>Next Job: <span class="text-gray-900 dark:text-gray-200">{{ $nextJob->name }}</span></p>
        <x-jet-button type="button" wire:click="print">Print</x-jet-button>
    </div>
    @endif
</div>
