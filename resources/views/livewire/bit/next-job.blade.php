<div class="px-4 py-5 space-y-2 text-gray-700 sm:px-6 dark:text-gray-400">
    @if ($nextJob)
    <div class="flex items-center justify-between px-4 py-5 space-y-2 text-gray-700 sm:px-6 dark:text-gray-400">
        <p>Next Job: <span class="text-gray-900 dark:text-gray-200">{{ $nextJob->name }}</span></p>
        <x-jet-button type="button" wire:click="print" :disabled="$printer->status === 'Printing'">Print</x-jet-button>
    </div>
    @else
    <p>Next Job: <span class="text-gray-900 dark:text-gray-200">None</span></p>
    @endif
</div>
