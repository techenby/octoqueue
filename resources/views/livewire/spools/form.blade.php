<x-jet-form-section submit="save">
    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="brand" value="{{ __('Brand') }}" />
            <x-jet-input id="brand" type="text" class="block w-full mt-1" wire:model="spool.brand" autofocus />
            <x-jet-input-error for="spool.brand" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="color_hex" value="{{ __('Color') }}" />
            <x-jet-input id="color_hex" type="color" class="mt-1" wire:model="spool.color_hex" />
            <x-jet-input-error for="spool.color_hex" class="mt-2" />
        </div>


        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="cost" value="{{ __('Cost') }}" />
            <x-jet-input id="cost" type="text" class="block w-full mt-1" wire:model="spool.cost" />
            <x-form.help>Used to calculate the cost of an individual print.</x-jet-help>
            <x-jet-input-error for="spool.cost" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="material" value="{{ __('Material') }}" />
            <x-jet-input id="material" type="text" class="block w-full mt-1" wire:model="spool.material" />
            <x-jet-input-error for="spool.material" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="diameter" value="{{ __('Diameter') }}" />
            <x-jet-input id="diameter" type="text" class="block w-full mt-1" wire:model="spool.diameter" />
            <x-jet-input-error for="spool.diameter" class="mt-2" />
        </div>

        @if($spool->id === null)
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="initial-weight" value="{{ __('Initial Weight') }}" />
            <x-jet-input id="initial-weight" type="text" class="block w-full mt-1" wire:model="initialWeight" />
            <x-form.help>Weigh the spool and input the result here or use a best guess.</x-jet-help>
            <x-jet-input-error for="initialWeight" class="mt-2" />
        </div>
        @endif

    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
