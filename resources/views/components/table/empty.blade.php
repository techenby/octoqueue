@props([
    'colspan' => 10,
    'route' => null,
    'label' => null
])

<td colspan="{{ $colspan }}">
    <div class="flex items-center justify-center w-full px-3 py-4">
        <a href="{{ route($route) }}">
            <div class="flex items-center text-lg font-semibold text-blue-700 group dark:text-blue-400 dark:hover:text-blue-300 hover:text-blue-500">
                <div>{{ $label }}</div>

                <div class="ml-1">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </a>
    </div>
</td>
