<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-850 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-400 uppercase tracking-widest shadow-sm hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:border-cyan-300 focus:ring focus:ring-cyan-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
