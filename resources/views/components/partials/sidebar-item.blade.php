@props([
    'route', 
    'label',
    ])

<a wire:navigate href="{{route($route)}}" class="flex items-center justify-between gap-2 p-3 hover:bg-brand-light dark:hover:bg-brand-dark rounded-md">
    <div class="flex gap-2">
        <div>
            {{ $svg }}
        </div>
        <h1 class="font-bold">{{ $label }}:</h1>
    </div>
    @if (isset($value))
        <p class="bg-gray-200 dark:bg-gray-700 px-2 rounded-md">{{ $value }}</p>
    @endif
    @if (isset($statusSvg))
        {{ $statusSvg }}
    @endif
</a>