<x-app-layout>
    <x-layout.header :title="__('Create Team')" />

    <x-ui.container>
        @livewire('teams.create-team-form')
    </x-ui.container>
</x-app-layout>
