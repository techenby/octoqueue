<x-slot name="header">
    <x-layout.header :title="$title" />
</x-slot>

<div class="p-6 mx-auto bg-white rounded-md shadow dark:border dark:border-gray-700 dark:bg-gray-850 md:w-1/2">
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-jet-button type="submit">
            Submit
        </x-jet-button>
    </form>
</div>
