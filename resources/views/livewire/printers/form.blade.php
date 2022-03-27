<x-jet-form-section submit="save">
    <x-slot name="title">Printer Configuration</x-slot>
    <x-slot name="description">Configure a printer that OctoQueue can control.</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Printer Name') }}" />
            <x-jet-input id="name" type="text" class="block w-full mt-1" wire:model="printer.name" placeholder="Friendly name for printer" autofocus />
            <x-jet-input-error for="printer.name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="model" value="{{ __('Model') }}" />
            <x-jet-input id="model" type="text" class="block w-full mt-1" wire:model="printer.model" placeholder="Ender 3 Pro" />
            <x-jet-input-error for="printer.model" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="url" value="{{ __('Url') }}" />
            <div class="relative flex items-stretch flex-grow mt-1 focus-within:z-10">
                <x-jet-input id="url" type="url" class="block w-full rounded-r-none" wire:model="printer.url" placeholder="https://octoeverywhere.com/shared-url" />
                <a href="/docs/where-to-get-printer-url" class="relative inline-flex items-center px-4 py-2 -ml-px space-x-2 text-sm font-medium text-gray-700 border border-gray-300 dark:text-gray-400 dark:border-gray-700 rounded-r-md bg-gray-50 dark:bg-gray-850 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <x-heroicon-s-question-mark-circle class="w-5 h-5 text-gray-400" />
                    <span class="sr-only">Where to get printer url?</span>
                </a>
            </div>
            <x-jet-input-error for="printer.url" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="api_key" value="{{ __('API Key') }}" />
            <x-jet-input id="api_key" type="password" class="block w-full mt-1" wire:model="printer.api_key" placeholder="String from OctoPrint" />
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
