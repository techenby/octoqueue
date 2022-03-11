<x-app-layout>
    <x-slot name="header">{{ __('Edit Printer') }}</x-slot>

    <livewire:printers.form :printer="$printer" />
</x-app-layout>
