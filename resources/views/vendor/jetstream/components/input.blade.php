@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-blue-300 dark:focus:border-blue-700 focus:ring focus:ring-blue-200 dark:focus:ring-blue-700 focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
