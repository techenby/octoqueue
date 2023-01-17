<div class="filament-tables-text-column px-3 py-3">
    @php
    $job = $getRecord()->currentlyPrinting();
    @endphp
    @if ($job)
    <p class="truncate">{{ $getRecord()->currentJob->isNotEmpty() ? $getRecord()->currentJob->first()->name : $job['job']['file']['name'] }}</p>
    <x-ui.progress-bar :progress="round($job['progress']['completion'], 2)" />
    @else
    <span>No job found</span>
    @endif
</div>
