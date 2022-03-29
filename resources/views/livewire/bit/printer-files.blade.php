<div class="border border-t-0 border-gray-300 rounded-md dark:border-gray-700">
    <x-form.label for="path" :value="__('Path')" sr-only />
    <x-form.input wire:model="path" leading="Path:" id="path" no-margin />
    <div class="h-64 p-2 overflow-scroll">
        @include('livewire.bit.printer-files.loop')
    </div>
</div>
