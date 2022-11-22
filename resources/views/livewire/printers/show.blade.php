<div>
    <x-layout.header :breadcrumbs="$breadcrumbs">
        <x-slot:title>
            <span>{{ $title }}</span>
            <x-ui.badge :color="$printer->statusColor">{{ $printer->status }}</x-ui.badge>
        </x-slot:title>

        <div class="space-x-2">
            <x-jet-secondary-button href="{{ route('printers.edit', $printer) }}">Edit</x-jet-secondary-button>
            <x-jet-danger-button wire:click="deletePrinter">Delete</x-jet-danger-button>
        </div>
    </x-layout.header>

    <x-ui.container>
        @includeWhen($printer->status === 'offline', 'livewire.printers.partials.offline-alert')
        @includeWhen($printer->status === 'closed', 'livewire.printers.partials.closed-alert')

        <div class="grid grid-cols-5 gap-4">
            <div class="col-span-2">
                <livewire:bit.assign-material :tools="$tools" />
            </div>
        </div>
    </x-ui.container>
</div>
