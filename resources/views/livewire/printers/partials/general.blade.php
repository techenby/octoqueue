<x-ui.card class="text-gray-700 dark:text-gray-400" title="Axis Controls">
    <div class="flex items-center justify-between p-4">
        <x-jet-button wire:click="motorsOff">
            <div wire:loading wire:target="motorsOff">
                <x-ui.spinner />
            </div>
            Motors Off
        </x-jet-button>
        <x-jet-button wire:click="fansOff">
            <div wire:loading wire:target="fansOff">
                <x-ui.spinner />
            </div>
            Fans Off
        </x-jet-button>
        <x-jet-button wire:click="fansOn">
            <div wire:loading wire:target="fansOn">
                <x-ui.spinner />
            </div>
            Fans On
        </x-jet-button>
    </div>
</x-ui.card>
