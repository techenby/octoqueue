<x-ui.card title="Connection">
    <form wire:submit.prevent="connect" class="p-4 space-y-6">
        {{ $this->connectionForm }}

        @if (in_array($printer->status, ['error', 'closed']))
        <x-jet-button type="submit" wire:target="connect">
            <div wire:loading>
                <x-ui.spinner />
            </div>
            Connect
        </x-jet-button>
        @else
        <x-jet-button type="button" wire:click="disconnect">
            <div wire:loading wire:target="disconnect">
                <x-ui.spinner />
            </div>
            Disconnect
        </x-jet-button>
        @endif
    </form>
</x-ui.card>
