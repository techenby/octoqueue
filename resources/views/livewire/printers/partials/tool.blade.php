<x-ui.card title="Tool">
    <form wire:submit.prevent="extrude" id="toolForm" class="p-4 space-y-6">
        {{ $this->toolForm }}

        <x-jet-button type="submit">
            <div wire:loading wire:target="extrude">
                <x-ui.spinner />
            </div>
            Submit
        </x-jet-button>
    </form>
</x-ui.card>
