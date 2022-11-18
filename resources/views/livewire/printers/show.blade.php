<x-slot name="header">
    <x-layout.header :breadcrumbs="$breadcrumbs">
        <x-slot:title>
            <span>{{ $title }}</span>
            <x-ui.badge :color="$printer->statusColor">{{ $printer->status }}</x-ui.badge>
        </x-slot:title>
    </x-layout.header>
</x-slot>

<div>
    @includeWhen($printer->status === 'offline', 'livewire.printers.partials.offline-alert')
    @includeWhen($printer->status === 'closed', 'livewire.printers.partials.closed-alert')
</div>
