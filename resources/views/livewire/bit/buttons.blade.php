<div class="space-x-1">
    @if ($currentJob)
    <x-jet-button type="button" wire:click="completed">Mark as Completed</x-jet-button>
    @endif
    @if ($status === 'Printing')
    <x-jet-danger-button type="button" wire:click="stop">Stop</x-jet-danger-button>
    @endif
</div>
