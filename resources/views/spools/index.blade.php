<x-app-layout>
    <x-slot name="header">{{ __('Spools') }}</x-slot>
    <x-slot name="action">
        <x-jet-button href="{{ route('spools.create') }}">Create</x-jet-button>
    </x-slot>

    <livewire:spools.table :spools="$spools" />
</x-app-layout>
