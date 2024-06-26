@props([
    'headerText' => '',
    'svg' => '',
    ])

@php

$svgHTML = "<x-svg." . $svg . " />"
    
@endphp

<h2 class="flex justify-between font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    <div class=" flex gap-x-2 max-sm:hidden mr-5">
        <div>
            @switch($svg)
                @case('user')
                    <x-svg.user />
                    @break
                @case('dashboard')
                    <x-svg.dashboard />
                    @break
                @default
                    <x-svg.chip />
            @endswitch
        </div>
        <div>
            {{ $headerText }}
        </div>
    </div>
    <div class="flex">
        <x-header-badges></x-header-badges>  
    </div>                 
</h2>