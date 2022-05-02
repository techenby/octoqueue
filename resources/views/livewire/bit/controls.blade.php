<div class="flex text-gray-700 divide-x divide-gray-200 dark:divide-gray-700 dark:text-gray-400">
    <div class="grid grid-cols-2 gap-2 p-4">
        <div>
            <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">X/Y Axis</h2>
            <div class="grid grid-cols-3">
                <div></div>
                <button type="button" wire:click="jog('y', '-)" class="-mb-px rounded-none rounded-t-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-up class="w-6 h-6"/>
                </button>
                <div></div>
                <button type="button" wire:click="jog('x', '-)" class="-mr-px rounded-none rounded-l-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-left class="w-6 h-6"/>
                </button>
                <button type="button" wire:click="home('xy')" class="rounded-none btn btn-base btn-outline">
                    <x-heroicon-o-home class="w-6 h-6"/>
                </button>
                <button type="button" wire:click="jog('x')" class="-ml-px rounded-none rounded-r-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-right class="w-6 h-6"/>
                </button>
                <div></div>
                <button type="button" wire:click="jog('y')" class="-mt-px rounded-none rounded-b-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-down class="w-6 h-6"/>
                </button>
                <div></div>
            </div>
        </div>
        <div>
            <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Z Axis</h2>
            <div class="grid grid-cols-3">
                <div></div>
                <button type="button" wire:click="jog('z')" class="-mb-px rounded-none rounded-t-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-up class="w-6 h-6"/>
                </button>
                <div></div>
                <div></div>
                <button type="button" wire:click="home('z')" class="rounded-none btn btn-base btn-outline">
                    <x-heroicon-o-home class="w-6 h-6"/>
                </button>
                <div></div>
                <div></div>
                <button type="button" wire:click="jog('z', '-')" class="-mt-px rounded-none rounded-b-md btn btn-base btn-outline">
                    <x-heroicon-o-chevron-down class="w-6 h-6"/>
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
    <div class="p-4">
        <h2 class="mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Tool</h2>
        <x-form.input type="number" wire:model="amount" />

        <div class="relative z-0 flex mt-2 rounded-md shadow-sm">
            <button type="button" wire:click="tool('extrude')" class="rounded-none rounded-l-md btn btn-block btn-base btn-outline">Extrude</button>
            <button type="button" wire:click="tool('retract')" class="-ml-px rounded-none btn btn-base btn-block btn-outline rounded-r-md">Retract</button>
        </div>
        <h2 class="mt-4 mb-4 text-lg text-center text-gray-900 dark:text-gray-200">Hotend</h2>
        <form wire:submit.prevent="tool('temperature')" class="inline-flex">
            <x-form.input type="number" class="rounded-r-none" wire:model="temperature" />
            <button type="submit" class="-ml-px rounded-none btn btn-base btn-outline rounded-r-md">Set</button>
        </div>
    </div>
</div>
