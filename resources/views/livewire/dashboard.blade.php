<div>
    <x-layout.header title="Dashboard" />

    <x-ui.container>
        <div class="grid grid-cols-3 gap-8">
            <div class="col-span-2 space-y-8">
                @include('livewire.dashboard.currently-printing', ['printers' => $currentlyPrinting])
                @include('livewire.dashboard.queued-jobs', ['queuedJobs' => $queuedJobs])
            </div>
            <div class="space-y-8">
                @include('livewire.dashboard.connection-issues', ['printers' => $connectionIssues])
                @include('livewire.dashboard.standby', ['printers' => $standby])
                @if (! $missingMaterials->isEmpty())
                    <livewire:bit.assign-material :tools="$missingMaterials" />
                @endif
            </div>
        </div>
    </x-ui.container>
</div>
