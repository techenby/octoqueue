<x-app-layout>
    <x-slot name="header">{{ __('Edit Spool') }}</x-slot>

    <livewire:spools.form :spool="$spool" />
</x-app-layout>
