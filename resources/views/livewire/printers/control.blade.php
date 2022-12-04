<x-ui.card class="text-gray-700 dark:text-gray-400" title="Axis Controls">
    <div class="flex justify-center">
        <div class="grid grid-cols-2 gap-2 p-4">
            <div>
                <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">X/Y Axis</h2>
                <div class="grid grid-cols-3">
                    <div></div>
                    <button type="button" wire:click="move('y', '-')" class="-mb-px rounded-none rounded-t-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-up class="w-6 h-6" />
                    </button>
                    <div></div>
                    <button type="button" wire:click="move('x', '-')" class="-mr-px rounded-none rounded-l-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-left class="w-6 h-6" />
                    </button>
                    <button type="button" wire:click="home(['x','y'])" class="rounded-none btn btn-base btn-outline">
                        <x-heroicon-o-home class="w-6 h-6" />
                    </button>
                    <button type="button" wire:click="move('x')" class="-ml-px rounded-none rounded-r-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-right class="w-6 h-6" />
                    </button>
                    <div></div>
                    <button type="button" wire:click="move('y')" class="-mt-px rounded-none rounded-b-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-down class="w-6 h-6" />
                    </button>
                    <div></div>
                </div>
            </div>
            <div>
                <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Z Axis</h2>
                <div class="grid grid-cols-3">
                    <div></div>
                    <button type="button" wire:click="move('z')" class="-mb-px rounded-none rounded-t-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-up class="w-6 h-6" />
                    </button>
                    <div></div>
                    <div></div>
                    <button type="button" wire:click="home(['z'])" class="rounded-none btn btn-base btn-outline">
                        <x-heroicon-o-home class="w-6 h-6" />
                    </button>
                    <div></div>
                    <div></div>
                    <button type="button" wire:click="move('z', '-')" class="-mt-px rounded-none rounded-b-md btn btn-base btn-outline">
                        <x-heroicon-o-chevron-down class="w-6 h-6" />
                    </button>
                    <div></div>
                </div>
            </div>
            <div class="flex items-center justify-center col-span-2 mt-4">
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    <button type="button" wire:click="$set('amount', '0.1')" class="rounded-none rounded-l-md btn btn-base btn-outline {{ $amount == 0.1 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">0.1mm</button>
                    <button type="button" wire:click="$set('amount', '1')" class="-ml-px rounded-none btn btn-base btn-outline {{ $amount == 1 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">1mm</button>
                    <button type="button" wire:click="$set('amount', '10')" class="-ml-px rounded-none btn btn-base btn-outline {{ $amount == 10 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">10mm</button>
                    <button type="button" wire:click="$set('amount', '100')" class="-ml-px rounded-none btn btn-base btn-outline rounded-r-md {{ $amount == 100 ? 'bg-gray-50 dark:bg-gray-850' : '' }}">100mm</button>
                </span>
            </div>
        </div>
    </div>
</x-ui.card>
