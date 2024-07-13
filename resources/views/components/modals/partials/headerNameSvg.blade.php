<div class="flex gap-3 items-center">
    <div>
        <p class="text-gray-300 dark:text-gray-600 text-xl font-extrabold">{{ $name }}</p>
        <p class="text-gray-500 dark:text-gray-400 text-xs text-right font-extrabold">{{ 'Running on: ' . $node }}</p>
    </div>
    <div>
        @switch($type)
            @case('vm')
                <x-svg.vm size="size-16 text-gray-300 dark:text-gray-700"/>
                @break
            @case('lxc')
                <x-svg.container size="size-16 text-gray-300 dark:text-gray-700"/>
                @break
            @default
                <x-svg.chip size="size-16 text-gray-300 dark:text-gray-700"/>
        @endswitch    
    </div>
</div>