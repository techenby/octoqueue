<div>
    <x-layout.header title="Dashboard" />

    <x-ui.container>
        <div class="grid grid-cols-3 gap-8">
            <div>
                @include('livewire.dashboard.connection-issues', ['printers' => $connectionIssues])
            </div>
            <div class="col-span-2">
                @include('livewire.dashboard.currently-printing', ['printers' => $currentlyPrinting])
            </div>
            <div>
                @include('livewire.dashboard.standby', ['printers' => $standby])
            </div>
            @if(! $missingMaterials->isEmpty())
            <div>
                <livewire:bit.assign-material :tools="$missingMaterials" />
            </div>
            @endif
        </div>
    </x-ui.container>
</div>
