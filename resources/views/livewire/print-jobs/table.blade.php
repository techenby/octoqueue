<div class="flex-col space-y-4">
    <x-table.header />

    <x-table>
        <x-slot:head>
            <tr>
                <x-table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">Name</x-table.th>
                <x-table.th sortable wire:click="sortBy('job_type_id')" :direction="$sortField === 'job_type_id' ? $sortDirection : null">Type</x-table.th>
                <x-table.th sortable wire:click="sortBy('printer_id')" :direction="$sortField === 'printer_id' ? $sortDirection : null">Printer</x-table.th>
                <x-table.th sortable wire:click="sortBy('user_id')" :direction="$sortField === 'user_id' ? $sortDirection : null">Creator</x-table.th>
                <x-table.th>Color</x-table.th>
                <x-table.th>Start Date</x-table.th>
                <x-table.th>Duration</x-table.th>
                <x-table.th>Filament Used</x-table.th>
                <x-table.th>
                    <span class="sr-only">Edit</span>
                    <span class="sr-only">Delete</span>
                </x-table.th>
            </tr>
        </x-slot>
        <x-slot:body>
            @forelse ($rows as $job)
            <tr>
                <x-table.td>{{ $job->name }}</x-table.th>
                <x-table.td muted>{{ $job->type->name ?? '-' }}</x-table.td>
                <x-table.th muted>{{ $job->printer->name ?? 'Any' }}</x-table.td>
                <x-table.td muted>{{ $job->user->name ?? '-' }}</x-table.td>
                <x-table.td>
                    <div class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700" style="background:{{ $job->color_hex }}">
                    </div>
                </x-table.td>
                <x-table.td muted><x-date :date="$job->started_at" format="M jS Y" /></x-table.td>
                <x-table.td muted><x-date :from="$job->started_at" :to="$job->completed_at" /></x-table.td>
                <x-table.td muted>{{ $job->completed ? $job->filament_used : '' }}</x-table.td>
                <x-table.td class="space-x-2">
                    <x-table.link wire:click="print({{ $job->id }})" color="green" :disabled="$job->started">Print<span class="sr-only" > {{ $job->name }}</x-table.link>
                    <x-table.link wire:click="duplicate({{ $job->id }})">Duplicate<span class="sr-only" > {{ $job->name }}</x-table.link>
                    <x-table.link :disabled="$job->started">Edit<span class="sr-only" > {{ $job->name }}</x-table.link>
                    <x-table.link wire:click="delete({{ $job->id }})" :disabled="$job->started && !$job->completed" danger>Delete<span class="sr-only"> {{ $job->name }}</x-table.link>
                </x-table.td>
            </tr>
            @empty
            <tr>
                <x-table.empty route="jobs.create" label="Create a Job" colspan="8" />
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{ $rows->links() }}
</div>
