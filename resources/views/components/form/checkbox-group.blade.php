@props(['model', 'id', 'label'])

<div class="px-4 py-2 -ml-px border border-gray-300 first:ml-0 hover:bg-gray-50 dark:hover:bg-gray-850 dark:border-gray-700 first:rounded-l-md last:rounded-r-md">
    <input type="checkbox" wire:model="{{ $model }}" value="{{ $id }}" class="absolute pointer-events-none peer" id="{{ $id }}" autocomplete="off" style="clip: rect(0,0,0,0);">
    <x-form.label class="whitespace-nowrap peer-checked:text-blue-500" :for="$id" :value="$label"></x-form.label>
</div>
