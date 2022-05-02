<div class="px-4 py-5 space-y-2 text-gray-700 sm:px-6 dark:text-gray-400">
    @if ($currentJob)
    <p>Current Job: <span class="text-gray-900 dark:text-gray-200">{{ $currentJob->name }}</span></p>
    <p>Started:
        <x-date :date="$currentJob->started_at" diff class="text-gray-900 dark:text-gray-200" />
    </p>
    <p>Progress: <span class="text-gray-900 dark:text-gray-200">{{ round($currentJobStatus?->progress['completion']) }}%</span></p>
    @else
    <p>Current Job: None</p>
    @endif
</div>
