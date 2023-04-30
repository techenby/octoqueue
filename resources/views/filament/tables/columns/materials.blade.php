<div class="filament-tables-text-column px-3 py-3">
    <x-icon.filament :x-tooltip.raw="$getRecord()->material->name ?? ''" class="w-12 h-12 text-gray-900 dark:text-gray-200" :fill="$getRecord()->material->color_hex ?? '#000000'" />
</div>
