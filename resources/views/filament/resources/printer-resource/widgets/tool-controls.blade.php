<x-filament::widget>
    <x-filament::card heading="Tool Controls">
        <form wire:submit.prevent="extrude" id="toolForm" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit">
                Submit
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::widget>
