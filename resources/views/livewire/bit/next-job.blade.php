<div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
    <p class="text-gray-700 dark:text-gray-400">Next Job: <span class="text-gray-900 dark:text-gray-200">{{ $printer->nextJob->name ?? 'None' }}</span></p>
    @if ($printer->status !== 'Printing' && $printer->nextJob !== null)
    <button wire:click="print" class="btn btn-sm btn-blue">Print</button>
    @endif
</div>
