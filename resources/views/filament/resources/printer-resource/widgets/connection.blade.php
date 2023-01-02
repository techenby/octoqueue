<x-filament::widget>
    <x-filament::card heading="Connection">
        <form wire:submit.prevent="connect" class="space-y-6">
            {{ $this->form }}

            @if (in_array($record->status, ['error', 'closed']))
            <x-filament::button type="submit" wire:click="connect">
                Connect
            </x-filament::button>
            @else
            <x-filament::button type="button" wire:click="disconnect">
                Disconnect
            </x-filament::button>
            @endif
        </form>
    </x-filament::card>
</x-filament::widget>
