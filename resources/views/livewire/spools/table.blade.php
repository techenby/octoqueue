<div>
    <x-bit.table>
        <x-slot:head>
            <tr>
                <x-bit.th>Location</x-bit.th>
                <x-bit.th>Brand</x-bit.th>
                <x-bit.th>Material</x-bit.th>
                <x-bit.th>Color</x-bit.th>
                <x-bit.th>Cost</x-bit.th>
                <x-bit.th>Weight</x-bit.th>
                <x-bit.th>Length</x-bit.th>
                <x-bit.th>
                    <span class="sr-only">Edit</span>
                </x-bit.th>
            </tr>
        </x-slot>
        <x-slot:body>
            @forelse ($spools as $spool)
            <tr>
                <x-bit.td>{{ $spool->location }}</x-bit.td>
                <x-bit.td>{{ $spool->brand }}</x-bit.td>
                <x-bit.td>{{ $spool->material }}</x-bit.td>
                <x-bit.td>
                    <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $spool->color_hex }}">
                        <span class="sr-only">{{ $spool->color }}</span>
                    </div>
                </x-bit.td>
                <x-bit.td>{{ $spool->cost }}</x-bit.td>
                <x-bit.td>{{ $spool->formattedCurrentWeight }}</x-bit.td>
                <x-bit.td>{{ $spool->formattedCurrentLength }}</x-bit.td>
                <x-bit.td>
                    <a href="{{ route('spools.edit', $spool) }}" class="text-blue-600 hover:text-blue-900">Edit<span class="sr-only"> {{ $spool->name }}</span></a>
                </x-bit.td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="flex items-center justify-center w-full px-3 py-4">
                        <a href="/spools/create">
                            <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                                <div>Create your first Spool</div>

                                <div class="ml-1">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-slot>
    </x-bit.table>
</div>
