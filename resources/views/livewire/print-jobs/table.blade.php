<div class="flex-col space-y-4">
    <div class="md:flex md:justify-between">
        <div class="flex flex-col space-y-4 md:items-end md:space-x-4 md:flex-row">
            <x-form.input wire:model="search" placeholder="Search..." />
        </div>
        <div class="flex items-end mt-4 space-x-2 md:mt-0">
            <x-bit.per-page />
        </div>
    </div>
    <div>
        <x-bit.table>
            <x-slot:head>
                <tr>
                    <x-bit.th>Name</x-bit.th>
                    <x-bit.th>Type</x-bit.th>
                    <x-bit.th>Printer</x-bit.th>
                    <x-bit.th>Creator</x-bit.th>
                    <x-bit.th>Color</x-bit.th>
                    <x-bit.th>Completed At</x-bit.th>
                    <x-bit.th>Filament Used</x-bit.th>
                    <x-bit.th>
                        <span class="sr-only">Edit</span>
                        <span class="sr-only">Delete</span>
                    </x-bit.th>
                </tr>
            </x-slot>
            <x-slot:body>
                @forelse ($rows as $job)
                <tr>
                    <x-bit.td>{{ $job->name }}</x-bit.th>
                    <x-bit.td muted>{{ $job->type->name }}</x-bit.td>
                    <x-bit.th muted>{{ $job->printer->name ?? 'Any' }}</x-bit.td>
                    <x-bit.td muted>{{ $job->user->name }}</x-bit.td>
                    <x-bit.td>
                        <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $job->color_hex }}">
                        </div>
                    </x-bit.td>
                    <x-bit.td muted>{{ $job->completed_at }}</x-bit.td>
                    <x-bit.td muted>{{ $job->completed ? $job->filament_used : '' }}</x-bit.td>
                    <x-bit.td class="space-x-2">
                        <a href="{{ route('jobs.edit', $job) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">Edit<span class="sr-only"> {{ $job->name }}</span></a>
                        <button type="button" wire:click="delete({{ $job->id }})" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete<span class="sr-only"> {{ $job->name }}</span></a>
                    </x-bit.td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="flex items-center justify-center w-full px-3 py-4">
                            <a href="/jobs/create">
                                <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                                    <div>Create a job</div>

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

    <div>
        {{ $rows->links() }}
    </div>
</div>
