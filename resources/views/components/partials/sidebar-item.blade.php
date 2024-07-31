@props([
    'route', 
    'label',
    'active',
    ])

@php
    $class = ($active ?? false)
                ? 'flex items-center justify-between gap-2 p-1 mb-1 bg-brand-light dark:bg-brand-dark text-gray-700 dark:text-gray-300 rounded-md'
                : 'flex items-center justify-between gap-2 p-1 mb-1 hover:bg-brand-light dark:hover:bg-brand-dark rounded-md transition-all';
    
    $classBadge = ($active ?? false)
                ? 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 rounded-md font-bold'
                : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 rounded-md font-bold';
                
@endphp

<a wire:navigate href="{{route($route)}}" class="{{ $class }}">
    <div class="flex items-center gap-2">
        <div>
            {{ $svg }}
        </div>
        <p class="font-bold">{{ $label }}:</p>
    </div>
    @if (isset($value))
        <p class="{{ $classBadge }}">{{ $value }}</p>
    @endif
    @if (isset($statusSvg))
        {{ $statusSvg }}
    @endif
</a>