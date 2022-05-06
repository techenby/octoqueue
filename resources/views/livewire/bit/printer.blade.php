<div wire:init="load" wire:poll.25s="$refresh" class="overflow-hidden bg-white divide-y divide-gray-300 rounded-md shadow dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700">
    <div class="flex items-center justify-between px-4 py-5 space-x-4 sm:px-6">
        <div class="flex items-center space-x-2">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $printer->name }}</h3>
            <span class="text-sm text-gray-700 dark:text-gray-400">({{ $status }})</span>
            @if ($printer->spool_id)
            <x-icon-filament class="w-8 h-8" style="fill: {{ $printer->spool->color_hex }}" />
            @endif
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
    <div x-data="{tab: @entangle('tab')}">
        <div>
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <x-form.select wire:model="tab" id="tabs" :options="$options"/>
            </div>
            <div class="hidden sm:block">
                <nav class="relative z-0 flex divide-x divide-gray-200 shadow dark:border-b dark:border-gray-700 dark:divide-gray-700" aria-label="Tabs">
                    @foreach ($options as $key => $option)
                    <button type="button" @click="tab = '{{ $key }}'"
                        class="relative flex-1 min-w-0 px-4 py-4 overflow-hidden text-sm font-medium text-center bg-white dark:bg-gray-800 group hover:bg-gray-50 dark:hover:bg-gray-850 focus:z-10"
                        :class="tab === '{{ $key }}' ? 'text-gray-900 dark:text-gray-200' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                    >
                        <span>{{ $option }}</span>
                        <span aria-hidden="true"
                            class="absolute inset-x-0 bottom-0 h-0.5"
                            :class="tab === '{{ $key }}' ? 'bg-blue-500' : 'bg-transparent'"
                        >
                        </span>
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>
        @foreach ($options as $key => $option)
            <div x-show="tab === '{{ $key }}'">
                @include('livewire.bit.' . $key)
            </div>
        @endforeach
    </div>
</div>
