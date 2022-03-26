<x-app-layout>
    <x-slot name="header">{{ __('Print Queue') }}</x-slot>
    <x-slot name="action">
        <x-jet-button href="{{ route('jobs.create') }}">Create</x-jet-button>
    </x-slot>

    <livewire:print-jobs.table />
</x-app-layout>
