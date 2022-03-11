<x-app-layout>
    <x-slot name="header">{{ __('Printers') }}</x-slot>
    <x-slot name="action">
        <x-jet-button href="{{ route('printers.create') }}">Create</x-jet-button>
    </x-slot>

    <livewire:printers.table :printers="$printers" />
</x-app-layout>
