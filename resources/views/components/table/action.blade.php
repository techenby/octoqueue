<button {{ $attributes->merge(['type' => 'button', 'class' => 'relative inline-flex text-sm -ml-px first:ml-0 items-center px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-700 first:rounded-l-md last:rounded-r-md hover:bg-gray-50 dark:hover:bg-gray-850 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-75 disabled:cursor-not-allowed']) }}>
    {{ $slot ?? $label }}
</button>
