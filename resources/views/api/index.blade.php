<x-app-layout>
    <x-layout.header :title="__('API Tokens')" />

    <x-ui.container>
        @livewire('api.api-token-manager')
    </x-ui.container>
</x-app-layout>
