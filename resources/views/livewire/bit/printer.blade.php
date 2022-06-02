<div wire:init="load" class="overflow-hidden bg-white divide-y divide-gray-300 rounded-md shadow dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700">
    <div class="flex items-center justify-between px-4 py-5 space-x-4 sm:px-6">
        <div class="flex items-center space-x-2">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $printer->name }}</h3>
            <span class="text-sm text-gray-700 dark:text-gray-400">({{ $status }})</span>
            <x-icon-filament class="w-8 h-8" style="fill: {{ $printer->spool->color_hex ?? 'transparent' }}" />
        </div>
        <div class="space-x-1">
            @if ($currentJob)
            <x-jet-button type="button" wire:click="completed">Mark as Completed</x-jet-button>
            @endif
            @if ($status === 'Printing')
            <x-jet-danger-button type="button" wire:click="stop">Stop</x-jet-danger-button>
            @endif
        </div>
    </div>
    <div class="relative lg:min-h-96">
        @include('livewire.bit.temps')
        <img id="{{ $printer->id }}-webcam'" src="{{ $printer->webcam }}" />
    </div>
    @include('livewire.bit.tabs')
</div>
