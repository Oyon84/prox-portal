@props([
    'active' => false,
    'number'
    ])

@php
    $class = ($active ?? false)
                ? 'text-gray-900 font-bold dark:text-gray-200'
                : 'text-gray-500 dark:text-gray-400';
@endphp

<a class="{{ $class }}" href="#" wire:click="setNumberTasks({{ $number }})">{{ $number }}</a>