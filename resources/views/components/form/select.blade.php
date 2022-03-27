@props([
    'options' => false,
    'placeholder' => false,
])

<select {{ $attributes->merge(['class' => 'mt-1 block dark:text-gray-200 dark:focus:bg-gray-800 focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm bg-transparent border-gray-300 dark:border-gray-700 rounded-md']) }} >
    @if ($placeholder)
    <option value="">{{ $placeholder }}</option>
    @endif

    {{ $slot }}

    @if ($options)
        @foreach ($options as $key => $option)
        <option value="{{ $option->id ?? $key }}">{{ $option->name ?? $option }}</option>
        @endforeach
    @endif
</select>
