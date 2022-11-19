<x-app-layout>
    <x-layout.header title="Dashboard" />

    <x-ui.container>
        <div class="grid grid-cols-3 gap-8">
            <livewire:dashboard.connection-issues />
            <livewire:dashboard.currently-printing />
        </div>
    </x-ui.container>
</x-app-layout>
