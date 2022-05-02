<div class="flex text-gray-700 divide-x divide-gray-200 dark:divide-gray-700 dark:text-gray-400">
    <div class="grid grid-cols-2 gap-2 p-4">
        <div>
            <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">X/Y Axis</h2>
            <div class="grid grid-cols-3">
                <div></div>
                <button type="button" wire:click="jog('y', false)" class="-mb-px rounded-none rounded-t-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-up class="w-6 h-6"/>
                </button>
                <div></div>
                <button type="button" wire:click="jog('x', false)" class="-mr-px rounded-none rounded-l-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-left class="w-6 h-6"/>
                </button>
                <button type="button" wire:click="home('xy')" class="rounded-none btn btn-base btn-outline">
                    <x-heroicon-o-home class="w-6 h-6"/>
                </button>
                <button type="button" wire:click="jog('x', true)" class="-ml-px rounded-none rounded-r-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-right class="w-6 h-6"/>
                </button>
                <div></div>
                <button type="button" wire:click="jog('y', true)" class="-mt-px rounded-none rounded-b-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-down class="w-6 h-6"/>
                </button>
                <div></div>
            </div>
        </div>
        <div>
            <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Z Axis</h2>
            <div class="flex flex-col items-center justify-center">
                <button type="button" class="rounded-none btn btn-base btn-outline rounded-t-md">
                    <x-heroicon-o-chevron-up class="w-6 h-6"/>
                </button>
                <button type="button" class="-mt-px rounded-none btn btn-base btn-outline">
                    <x-heroicon-o-home class="w-6 h-6"/>
                </button>
                <button type="button" class="-mt-px rounded-none btn btn-base btn-outline rounded-b-md">
                    <x-heroicon-o-chevron-down class="w-6 h-6"/>
                </button>
            </div>
        </div>
        <div class="flex items-center justify-center col-span-2 mt-4">
            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                <button type="button" class="rounded-none rounded-l-md btn btn-base btn-outline">0.1mm</button>
                <button type="button" class="-ml-px rounded-none btn btn-base btn-outline">1mm</button>
                <button type="button" class="-ml-px rounded-none btn btn-base btn-outline">10mm</button>
                <button type="button" class="-ml-px rounded-none btn btn-base btn-outline rounded-r-md">100mm</button>
            </span>
        </div>
    </div>
    <div class="p-4">
        <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Tool</h2>
        <x-form.input type="number" wire:model="amount" />

        <div class="relative z-0 flex mt-2 rounded-md shadow-sm">
            <button type="button" class="rounded-none rounded-l-md btn btn-block btn-base btn-outline">Extrude</button>
            <button type="button" class="-ml-px rounded-none btn btn-base btn-block btn-outline rounded-r-md">Retract</button>
        </div>
    </div>
</div>
