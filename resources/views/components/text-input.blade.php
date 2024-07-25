@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-brand-dark dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
