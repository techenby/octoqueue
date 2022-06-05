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
    <div class="relative tvstatic lg:h-96 group">
        <img id="{{ $printer->id }}-screenshot'" class="object-cover w-full h-96" src="{{ $printer->screenshot }}" />

        <button x-data @click="Livewire.emit('pip', {{ $printer->id }})" class="absolute inset-0 z-10 flex items-center justify-center w-full h-full opacity-0 group-hover:opacity-100">
            <x-heroicon-o-play class="w-48 h-48 text-blue-500" />
        </button>
    </div>
</x-ui.card>
