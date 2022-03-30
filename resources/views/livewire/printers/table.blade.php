<div>
    <x-bit.table>
        <x-slot:head>
            <tr>
                <x-bit.th>Name</x-bit.th>
                <x-bit.th>Model</x-bit.th>
                <x-bit.th>Spool</x-bit.th>
                <x-bit.th>Status</x-bit.th>
                <x-bit.th>
                    <span class="sr-only">Edit</span>
                </x-bit.th>
            </tr>
        </x-slot>
        <x-slot:body>
            @forelse ($printers as $printer)
            <tr>
                <x-bit.td>{{ $printer->name }}</x-bit.td>
                <x-bit.td muted>{{ $printer->model }}</x-bit.td>
                <x-bit.td muted>{{ $printer->spool->name ?? 'None loaded' }}</x-bit.td>
                <x-bit.td muted>{{ true ? 'loading' : $printer->status }}</x-bit.td>
                <x-bit.td>
                    <a href="{{ route('printers.edit', $printer) }}" class="text-blue-600 hover:text-blue-900">Edit<span class="sr-only"> {{ $printer->name }}</span></a>
                </x-bit.td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="flex items-center justify-center w-full px-3 py-4">
                        <a href="/printers/create">
                            <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                                <div>Create your first printer</div>

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
