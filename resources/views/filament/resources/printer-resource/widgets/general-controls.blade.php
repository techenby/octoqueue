<x-filament::widget>
    <x-filament::card heading="General Controls">
        <div class="flex flex-col space-y-2">
            <x-filament::button type="button" wire:click="motorsOff">
                Motors Off
            </x-filament::button>
            <x-filament::button type="button" wire:click="fansOff">
                Fans Off
            </x-filament::button>
            <x-filament::button type="button" wire:click="fansOn">
                Fans On
            </x-filament::button>
        </div>
    </x-filament::card>
</x-filament::widget>
