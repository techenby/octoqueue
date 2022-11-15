<x-slot name="header">
    <x-layout.header :title="$title" :breadcrumbs="$breadcrumbs" />
</x-slot>

<div class="p-6 mx-auto bg-white border border-gray-300 shadow rounded-xl dark:border-gray-600 dark:bg-gray-800 md:w-1/2">
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-jet-button type="submit">
            Submit
        </x-jet-button>
    </form>
</div>
