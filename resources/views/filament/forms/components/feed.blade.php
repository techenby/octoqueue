<x-dynamic-component :component="$getFieldWrapperView()" :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-action="$getHintAction()" :hint-color="$getHintColor()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            <template x-for="entry in state">
                <li>
                    <div class="py-3 flex justify-between">
                        <p class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                            <x-heroicon-o-scale class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" />
                            <span x-text="entry.weight + 'g'"></span>
                        </p>
                        <p class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                            <x-heroicon-o-clock class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" />
                            <span x-timeago="entry.timestamp"></span>
                        </p>
                    </div>
                </li>
            </template>
        </ul>
    </div>
</x-dynamic-component>
