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

    <x-ui.container full>
        @includeWhen($printer->status === 'offline', 'livewire.printers.partials.offline-alert')
        @includeWhen($printer->status === 'closed', 'livewire.printers.partials.closed-alert')

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @include('livewire.printers.partials.webcam')
            @include('livewire.printers.partials.control')
            @include('livewire.printers.partials.temps')
            <livewire:bit.assign-material :tools="$tools" />
            @include('livewire.printers.partials.connection')
            @include('livewire.printers.partials.tool')
            @include('livewire.printers.partials.general')
        </div>
    </x-ui.container>
</div>
