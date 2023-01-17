<x-filament::widget>
    <x-filament::card heading="Axis Controls">
        <div class="grid grid-cols-2 gap-2">
            <div>
                <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">X/Y Axis</h2>
                <div class="grid grid-cols-3 h-28 w-28">
                    <div></div>
                    <button type="button" wire:click="move('y', '-')"  class="justify-center -mb-px rounded-none rounded-t-md btn btn-outline">
                        <x-heroicon-o-chevron-up class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                    <button type="button" wire:click="move('x', '-')" class="justify-center -mr-px rounded-none rounded-l-md btn btn-outline">
                        <x-heroicon-o-chevron-left class="w-5 h-5 shrink-0" />
                    </button>
                    <button type="button" wire:click="home(['x','y'])" class="justify-center btn btn-outline">
                        <x-heroicon-o-home class="w-5 h-5 shrink-0" />
                    </button>
                    <button type="button" wire:click="move('x')" class="justify-center rounded-none -ml-px rounded-r-md btn btn-outline">
                        <x-heroicon-o-chevron-right class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                    <button type="button" wire:click="move('y')" class="justify-center rounded-none -mt-px rounded-b-md btn btn-outline">
                        <x-heroicon-o-chevron-down class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                </div>
            </div>
            <div>
                <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Z Axis</h2>
                <div class="grid grid-cols-3 h-28 w-28">
                    <div></div>
                    <button type="button" wire:click="move('z')" class="justify-center -mb-px rounded-none rounded-t-md btn btn-outline">
                        <x-heroicon-o-chevron-up class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                    <div></div>
                    <button type="button" wire:click="home(['z'])" class="justify-center rounded-none btn btn-outline">
                        <x-heroicon-o-home class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                    <div></div>
                    <button type="button" wire:click="move('z', '-')" class="justify-center -mt-px rounded-none rounded-b-md btn btn-outline">
                        <x-heroicon-o-chevron-down class="w-5 h-5 shrink-0" />
                    </button>
                    <div></div>
                </div>
            </div>
            <div class="flex items-center justify-center col-span-2 mt-4">
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    <button type="button" wire:click="$set('amount', '0.1')" class="rounded-none rounded-l-md btn btn-xs btn-outline {{ $amount == 0.1 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">0.1mm</button>
                    <button type="button" wire:click="$set('amount', '1')" class="-ml-px rounded-none btn btn-xs btn-outline {{ $amount == 1 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">1mm</button>
                    <button type="button" wire:click="$set('amount', '10')" class="-ml-px rounded-none btn btn-xs btn-outline {{ $amount == 10 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">10mm</button>
                    <button type="button" wire:click="$set('amount', '100')" class="-ml-px rounded-none btn btn-xs btn-outline rounded-r-md {{ $amount == 100 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">100mm</button>
                </span>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
