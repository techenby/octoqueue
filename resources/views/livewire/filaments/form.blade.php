<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('Create Filament') }}
    </h2>
</x-slot>

<div class="p-6 bg-white rounded-md shadow dark:border dark:border-gray-700 dark:bg-gray-850 md:w-1/2">
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-jet-button type="submit">
            Submit
        </x-jet-button>
    </form>
</div>
