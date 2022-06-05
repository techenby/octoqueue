<x-ui.card class="{{ count($printers) == 1 ? 'col-span-2' : '' }}">
    <x-ui.card.header>
        <x-slot:left>
            <div class="flex items-center space-x-2">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-200">{{ $printer->name }}</h3>
                <span class="text-sm text-gray-700 dark:text-gray-400">({{ $printer->status }})</span>
                <button
                    x-data
                    x-tooltip="{{ $printer->spool->color }}"
                >
                    <x-icon-filament
                        class="w-8 h-8"
                        style="fill: {{ $printer->spool->color_hex ?? 'transparent' }}"
                    />
                </button>
                <button x-data @click="Livewire.emit('pip', {{ $printer->id }})">
                    <x-heroicon-o-camera class="w-6 h-6 text-gray-700 dark:text-gray-400" />
                </button>
            </div>
        </x-slot>
    </x-ui.card.header>
    <div class="px-4 py-5">
        <p>Next Job: <span class="text-gray-900 dark:text-gray-200">{{ $printer->nextJob->name ?? 'None' }}</span></p>
    </div>
    <div class="px-4 py-5">
        Spinner...
    </div>
</x-ui.card>
