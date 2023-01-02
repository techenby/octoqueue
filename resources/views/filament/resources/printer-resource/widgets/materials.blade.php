<x-filament::widget>
    <x-filament::card heading="Assign Materials">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($tools as $id => $tool)
            <li class="flex items-center justify-between space-x-4">
                <div>
                    @if($tool->has('printer'))
                    <p class="inline text-gray-900 dark:text-gray-200">{{ $tool->printer->name }}</p>
                    @endif
                    <label for="tool.{{ $id }}.material_id" class="inline text-gray-700 dark:text-gray-400">{{ $tool['name'] }}</label>
                </div>
                <select name="tool.{{ $id }}.material_id" id="tool.{{ $id }}.material_id" wire:model="tools.{{ $id }}.material_id" class="block w-full text-gray-900 transition duration-75 border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:border-gray-600">
                    <option value="">Select an option</option>
                    @foreach($materialOptions as $value => $option)
                    <option value="{{ $value }}">{{ $option }}</option>
                    @endforeach
                </select>
            </li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament::widget>
