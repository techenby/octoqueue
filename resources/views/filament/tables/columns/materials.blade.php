<div class="filament-tables-text-column px-3 py-3">
    @foreach($getRecord()->tools as $tool)
    <x-icon.filament :x-tooltip.raw="$tool->name ?? '' .' - ' . $tool->material->name ?? ''" class="w-12 h-12 text-gray-900 dark:text-gray-200" :fill="$tool->material->color_hex ?? '#000000'" />
    @endforeach
</div>
