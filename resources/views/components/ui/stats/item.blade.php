@props(['label' => 'Define a label', 'value' => 'Define a value'])

<div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800 dark:border dark:border-gray-700 sm:p-6">
    <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">{{ $label }}</dt>
    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-200">{{ $value }}</dd>
</div>
