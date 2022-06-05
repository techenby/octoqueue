<div class="flex-col space-y-4">
    <x-table.header>
        <x-slot:right>
            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                <x-table.action wire:click="showSetModal('job_type_id')" :disabled="$isDisabled['change-type']" class="space-x-1">
                    <x-heroicon-o-switch-vertical class="w-4 h-4" /><span class="sr-only">Change</span> <span>Type</span>
                </x-table.action>
                <x-table.action wire:click="showSetModal('color_hex')" :disabled="$isDisabled['change-color']" class="space-x-1">
                    <x-heroicon-o-switch-vertical class="w-4 h-4" /><span class="sr-only">Change</span> <span>Color</span>
                </x-table.action>
                <x-table.action wire:click="showSetModal('printer_id')" :disabled="$isDisabled['set-printer']" class="space-x-1">
                    <span>Set</span> <x-heroicon-o-printer class="w-4 h-4" /><span class="sr-only">Printer</span>
                </x-table.action>
                <x-table.action wire:click="massDelete" :disabled="$isDisabled['delete']">
                    <x-heroicon-o-trash class="w-4 h-4" /><span class="sr-only">Delete</span>
                </x-table.action>
            </span>
        </x-slot:right>
    </x-table.header>

    <x-table>
        <x-slot:head>
            <tr>
                <x-table.th class="w-8 pr-0">
                    <x-form.checkbox wire:model="selectPage" />
                </x-table.th>
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
            @if ($selectPage)
                <tr class="bg-gray-200 dark:bg-gray-850" wire:key="row-message">
                    <x-table.td colspan="11">
                        @unless ($selectAll)
                        <div class="text-center">
                            <span>You have selected <strong>{{ $rows->count() }}</strong> rows, do you want to select all <strong>{{ number_format($rows->total()) }}</strong>?</span>
                            <x-table.link wire:click="$toggle('selectAll')">Select All</x-table.link>
                        </div>
                        @else
                        <div class="text-center">You have selected all <strong>{{ number_format($rows->total()) }}</strong> rows.</div>
                        @endif
                    </x-table.td>
                </tr>
            @endif

            @forelse ($rows as $job)
            <tr wire:key="row-{{ $job->id }}">
                <x-table.td class="pr-0">
                    <x-form.checkbox wire:model="selected" value="{{ $job->id }}" />
                </x-table.td>
                <x-table.td>{{ $job->name }}</x-table.th>
                <x-table.td muted>{{ $job->type->name ?? '-' }}</x-table.td>
                <x-table.th muted>{{ $job->printer->name ?? 'Any' }}</x-table.td>
                <x-table.td muted>{{ $job->user->name ?? '-' }}</x-table.td>
                <x-table.td>
                    <div
                        x-data
                        x-tooltip="{{ $job->color_hex ?? 'Any' }}"
                        class="w-4 h-4 border border-gray-300 rounded dark:border-gray-700"
                        style="background: {{ $job->color_hex ?? 'linear-gradient(135deg,rgba(255, 0, 0, 1) 0%,rgba(208, 222, 33, 1) 20%,rgba(63, 218, 216, 1) 40%,rgba(28, 127, 238, 1) 60%,rgba(186, 12, 248, 1) 80%,rgba(255, 0, 0, 1) 100%)' }}"
                    >
                    </div>
                </x-table.td>
                <x-table.td muted><x-date :date="$job->started_at" format="M jS Y" /></x-table.td>
                <x-table.td muted><x-date :from="$job->started_at" :to="$job->completed_at" /></x-table.td>
                <x-table.td muted>{{ $job->completed ? $job->filament_used : '' }}</x-table.td>
                <x-table.td x-data class="space-x-2">
                    <x-table.link x-tooltip="Print" wire:click="print({{ $job->id }})" color="green" :disabled="$job->started">
                        <x-heroicon-o-printer class="w-4 h-4" />
                        <span class="sr-only">Print {{ $job->name }}</span>
                    </x-table.link>
                    <x-table.link x-tooltip="Duplicate" wire:click="duplicate({{ $job->id }})">
                        <x-heroicon-o-duplicate class="w-4 h-4" />
                        <span class="sr-only">Duplicate {{ $job->name }}</span>
                    </x-table.link>
                    <x-table.link x-tooltip="Edit" :disabled="$job->started">
                        <x-heroicon-o-pencil class="w-4 h-4" />
                        <span class="sr-only">Edit {{ $job->name }}</span>
                    </x-table.link>
                    <x-table.link x-tooltip="Delete" wire:click="delete({{ $job->id }})" :disabled="$job->started && !$job->completed" danger>
                        <x-heroicon-o-trash class="w-4 h-4" />
                        <span class="sr-only">Delete {{ $job->name }}
                    </x-table.link>
                </x-table.td>
            </tr>
            @empty
            <tr>
                <x-table.empty route="jobs.create" label="Create a Job" colspan="11" />
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{ $rows->links() }}

   <x-jet-dialog-modal wire:model="setModal">
        <x-slot name="title">
            {{ __('Swap ' . $modalLabel) }}
        </x-slot>

        <x-slot name="content">
            <x-form.label for="current-spool" value="Choose" sr-only />
            <x-form.select id="current-spool" wire:model="setValue" :options="$options[$modalType]" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="resetModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-3" wire:click="massSet" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
