<div>
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
                <x-form.select id="color" class="block w-full mt-1" placeholder="Any Color" wire:model="job.color_hex" :options="$colors" />
                <x-jet-input-error for="job.color" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="job-type" value="{{ __('Job Type') }}" />
                <x-form.select id="job-type" class="block w-full mt-1" wire:model="job.job_type_id" placeholder="Select Job Type" :options="$types" />
                <x-jet-input-error for="job.job_type_id" class="mt-2" />
            </div>

            <div class="col-span-6 space-y-4">
                @foreach ($printers as $printer)
                    <x-jet-label class="mb-1" value="{{ __('File for') }} {{ $printer->name }}" />
                    @if (isUrlAccessible($printer->url) && $printer->status !== 'Connection Error')
                    <div class="border border-gray-300 rounded-md dark:border-gray-700">
                        <div class="relative p-1">
                            <x-form.label for="path" :value="__('Path')" sr-only />
                            <x-form.input wire:model="files.{{ $printer->id }}" leading="Path:" id="path" no-margin />
                            <x-jet-button type="button" wire:click="showUpload({{ $printer->id }})" class="absolute top-2 right-2">Upload</x-jet-button>
                        </div>
                        <div class="h-64 p-2 space-y-2 overflow-scroll">
                            @include('livewire.print-jobs.files.loop', ['files' => $printer->files()])
                        </div>
                    </div>
                    @else
                    <p class="text-sm italic text-gray-700 dark:text-gray-400">The printer is not currently avilable, try to create jobs for this printer later.</p>
                    @endif
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

    <x-jet-dialog-modal wire:model="showUploadModal">
        <x-slot name="title">
            {{ __('Upload New File') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-form.label for="path" :value="__('Upload Path')" sr-only />
                    <x-form.input wire:model="uploadPath" leading="Upload Path:" id="path" no-margin />
                </div>

                <x-form.file wire:model="gcode" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="resetUploadModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-3" wire:click="uploadFile" wire:loading.attr="disabled">
                {{ __('Upload') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
