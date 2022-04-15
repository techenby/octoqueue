<div class="space-y-4">
    <x-table.header />

    <x-table>
        <x-slot:head>
            <tr>
                <x-table.th>Location</x-table.th>
                <x-table.th sortable wire:click="sortBy('brand')" :direction="$sortField === 'brand' ? $sortDirection : null">Brand</x-table.th>
                <x-table.th sortable wire:click="sortBy('material')" :direction="$sortField === 'material' ? $sortDirection : null">Material</x-table.th>
                <x-table.th sortable wire:click="sortBy('color')" :direction="$sortField === 'color' ? $sortDirection : null">Color</x-table.th>
                <x-table.th sortable wire:click="sortBy('cost')" :direction="$sortField === 'cost' ? $sortDirection : null">Cost</x-table.th>
                <x-table.th>Weight</x-table.th>
                <x-table.th>Length</x-table.th>
                <x-table.th>
                    <span class="sr-only">Edit</span>
                </x-table.th>
            </tr>
        </x-slot>
        <x-slot:body>
            @forelse ($rows as $spool)
            <tr>
                <x-table.td>{{ $spool->location }}</x-table.td>
                <x-table.td>{{ $spool->brand }}</x-table.td>
                <x-table.td>{{ $spool->material }}</x-table.td>
                <x-table.td>
                    <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $spool->color_hex }}">
                        <span class="sr-only">{{ $spool->color }}</span>
                    </div>
                </x-table.td>
                <x-table.td>{{ $spool->formattedCurrentWeight }}</x-table.td>
                <x-table.td>{{ $spool->formattedCost }}</x-table.td>
                <x-table.td>{{ $spool->formattedCurrentLength }}</x-table.td>
                <x-table.td>
                    <x-table.link href="{{ route('spools.edit', $spool) }}">Edit<span class="sr-only"> {{ $spool->name }}</x-table.link>
                </x-table.td>
            </tr>
            @empty
            <tr>
                <x-table.empty route="spools.create" label="Create a Spool" colspan="8" />
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{ $rows->links() }}
</div>
