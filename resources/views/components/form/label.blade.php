@props(['value' => null, 'srOnly' => false])

<label {{ $attributes->merge(['class' => $srOnly ? 'sr-only' : 'block font-medium text-sm text-gray-700 dark:text-gray-400']) }}>
    {{ $value ?? $slot }}
</label>
