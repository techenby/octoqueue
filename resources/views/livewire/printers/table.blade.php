<div class="space-y-4">
    <x-table.header />

    <x-table>
        <x-slot:head>
            <tr>
                <x-table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">Name</x-table.th>
                <x-table.th sortable wire:click="sortBy('model')" :direction="$sortField === 'model' ? $sortDirection : null">Model</x-table.th>
                <x-table.th sortable wire:click="sortBy('spool_id')" :direction="$sortField === 'spool_id' ? $sortDirection : null">Spool</x-table.th>
                <x-table.th>
                    <span class="sr-only">Edit</span>
                </x-table.th>
            </tr>
        </x-slot>
        <x-slot:body>
            @forelse ($rows as $printer)
            <tr>
                <x-table.td>{{ $printer->name }}</x-table.td>
                <x-table.td muted>{{ $printer->model }}</x-table.td>
                <x-table.td muted>
                    <button type="button" class="flex space-x-2 items-center group rounded px-1.5 py-1 -mx-1.5 -my-1 hover:bg-gray-50 dark:hover:bg-gray-850" wire:click="showSpoolModal({{ $printer->id }})">
                        <span>{{ $printer->spool->name ?? 'None loaded' }}</span>
                        <x-heroicon-o-chevron-down class="invisible w-4 h-4 group-hover:visible" />
                    </button>
                </x-table.td>
                <x-table.td>
                    <x-table.link href="{{ route('printers.edit', $printer) }}">Edit<span class="sr-only"> {{ $printer->name }}</x-table.link>
                </x-table.td>
            </tr>
            @empty
            <tr>
                <x-table.empty route="printers.create" label="Create a Printer" colspan="5" />
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{ $rows->links() }}

    <x-jet-dialog-modal wire:model="spoolModal">
        <x-slot name="title">
            {{ __('Swap Spool') }}
        </x-slot>

        <x-slot name="content">
            <x-form.label for="current-spool" value="Choose Spool" sr-only />
            <x-form.select id="current-spool" wire:model="currentSpool" :options="$spools" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="resetSpoolModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-3" wire:click="updateSpool" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
