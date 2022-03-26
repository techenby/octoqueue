<x-jet-form-section submit="save">
    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Printer Name') }}" />
            <x-jet-input id="name" type="text" class="block w-full mt-1" wire:model="printer.name" autofocus />
            <x-jet-input-error for="printer.name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="model" value="{{ __('Model') }}" />
            <x-jet-input id="model" type="text" class="block w-full mt-1" wire:model="printer.model" />
            <x-jet-input-error for="printer.model" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="url" value="{{ __('Url') }}" />
            <x-jet-input id="url" type="url" class="block w-full mt-1" wire:model="printer.url" />
            <x-jet-input-error for="printer.url" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="api_key" value="{{ __('API Key') }}" />
            <x-jet-input id="api_key" type="password" class="block w-full mt-1" wire:model="printer.api_key" />
            <x-jet-input-error for="printer.api_key" class="mt-2" />
        </div>

        @if(!$spools->isEmpty())
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="spool_id" value="{{ __('Loaded Spool') }}" />
            <x-form.select id="spool_id" class="block w-full mt-1" wire:model.defer="printer.spool_id" placeholder="No spool" :options="$spools" />
            <x-jet-input-error for="spool_id" class="mt-2" />
        </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ $printer->id === null ? __('Create') : __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
