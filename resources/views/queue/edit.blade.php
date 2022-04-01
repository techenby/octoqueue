<x-app-layout>
    <x-slot name="header">{{ __('Edit Print Job') }}</x-slot>

    <livewire:print-jobs.form :job="$job" />
</x-app-layout>
