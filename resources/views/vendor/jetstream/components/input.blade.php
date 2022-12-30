@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 text-sm dark:border-gray-700 bg-transparent text-gray-900 dark:text-gray-200 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
