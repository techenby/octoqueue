<x-ui.card title="Connection">
    <form wire:submit.prevent="connect" class="p-4 space-y-6">
        {{ $this->form }}

        @if (in_array($printer->status, ['error', 'closed']))
        <x-jet-button type="submit">Connect</x-jet-button>
        @else
        <x-jet-button type="button" wire:click="disconnect">Disconnect</x-jet-button>
        @endif
    </form>
</x-ui.card>
