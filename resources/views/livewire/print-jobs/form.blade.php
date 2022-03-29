<x-jet-form-section submit="save">
    <x-slot name="title">Job Configuration</x-slot>
    <x-slot name="description">Configure a job for the printer(s).</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-form.input id="name" type="text" class="block w-full mt-1" wire:model="job.name" autofocus />
            <x-jet-input-error for="job.name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="color" value="{{ __('Color') }}" />
            <x-form.select id="color" class="block w-full mt-1" placeholder="Select Color" wire:model="job.color_hex" :options="$colors" />
            <x-jet-input-error for="job.color" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="job-type" value="{{ __('Job Type') }}" />
            <x-form.select id="job-type" class="block w-full mt-1" wire:model="job.job_type_id" placeholder="Select Job Type" :options="$types" />
            <x-jet-input-error for="job.job_type_id" class="mt-2" />
        </div>

        <div class="col-span-6 space-y-4">
            @foreach ($printers as $printer)
            <div wire:key="printer-files-section-{{ $printer->id }}">
                <x-jet-label class="mb-1" value="{{ __('File for') }} {{ $printer->name }}" />
                <livewire:bit.printer-files :printer="$printer" wire:key="printer-files-{{ $printer->id }}" />
            </div>
            @endforeach
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="notes" value="{{ __('Notes') }}" />
            <x-form.textarea id="notes" class="block w-full mt-1" wire:model="job.notes" />
            <x-jet-input-error for="job.notes" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="quantity">Quantity</x-jet-label>
            <div class="flex">
                <div class="flex items-center mt-1 space-x-2 border border-gray-300 rounded-full dark:border-gray-700">
                    <button type="button" wire:click="adjustQuantity('minus')" class="p-1 text-gray-700 rounded-full dark:text-gray-400 hover:bg-indigo-500 hover:text-gray-200"><x-heroicon-o-minus class="w-6 h-6" /></button>
                    <input wire:model="quantity" class="w-6 text-lg text-center text-gray-900 bg-transparent dark:text-gray-200" />
                    <button type="button" wire:click="adjustQuantity('add')" class="p-1 text-gray-700 rounded-full dark:text-gray-400 hover:bg-indigo-500 hover:text-gray-200"><x-heroicon-o-plus class="w-6 h-6" /></button>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ $job->id === null ? __('Create') : __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
