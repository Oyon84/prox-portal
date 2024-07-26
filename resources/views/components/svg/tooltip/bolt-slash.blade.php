@props([
    'size' => 'size-6',
    'count' => '0',
    ])

<div class="group relative flex gap-1">
    <div class="flex items-center gap-x-1">
        <x-svg.bolt-slash size="{{ $size }}" />
        <span class="group-hover:opacity-100 transition-opacity delay-300 dark:bg-gray-100 bg-gray-800 px-1 text-xs dark:text-gray-800 text-gray-100 rounded-sm absolute left-1/2 -translate-x-1/2 translate-y-full opacity-0 m-4 mx-auto">Offline</span>    
    </div>
    {{ $count }} 
</div>  