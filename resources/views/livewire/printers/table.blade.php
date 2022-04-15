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
                <x-table.td muted>{{ $printer->spool->name ?? 'None loaded' }}</x-table.td>
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
</div>
