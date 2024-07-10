@props([
    'name',
    'nameSub',
    'details',
    'detailsSub',
    'status',
    'ip',
    'favorite',
    'vmid' => null,
    'node' => null,
    'type' => null,
    ])

@php
    $data = [
        'vmid' => $vmid,
        'node' => $node,
        'type' => $type,
    ]

@endphp

<tr class="group">
    <td class="pl-7 py-1 whitespace-nowrap">
        <div class="group-hover:hidden items-center h-10 w-10">
            {{ $svg }}
        </div>
        <div class="hidden group-hover:block items-center h-10 w-10">
            <a href="">
                <x-svg.edit size="size-5 text-gray-500" />
            </a>        </div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <a href=""><div class="flex items-center">
            <div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-200 group-hover:text-gray-600 dark:group-hover:text-gray-400">
                    {{ $name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $nameSub }}
                </div>
            </div>
        </div></a>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900 dark:text-gray-200 group-hover:text-gray-600 dark:group-hover:text-gray-400">{{ $details }}</div>
        <div class="text-xs text-gray-500">{{ $detailsSub }}</div>
    </td>
    <td class="flex gap-2 items-center px-6 py-2 whitespace-nowrap">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-md {{ $status == 'Stopped' ? "bg-red-200 text-red-800 ring-1 ring-red-300 group-hover:bg-red-300" : "bg-green-200 text-green-800 ring-1 ring-green-300 group-hover:bg-green-300" }} ">
            {{ $status }}
        </span>
        @if ($status == 'Stopped' && $type != null)
            <a href="#" wire:click.prevent="startInstance({{ $vmid }}, '{{ $node}}', '{{ $type }}')" class="text-green-600 hover:text-green-900"><x-svg.play /></a>
        @elseif ($status == 'Running' && $type != null)
            <a href="#" wire:click.prevent="stopInstance({{ $vmid }})" class="text-red-400 hover:text-red-800"><x-svg.stop /></a>
        @endif
    </td>
    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
        {{ $ip }}
    </td>
    <td class="flex items-center justify-between px-6 py-3 whitespace-nowrap  text-sm font-medium">
        <div class="flex gap-2">
            <a href="#" class=""><x-svg.favorite /></a>
        </div>
        <div class="flex gap-2">
            <a href="#" class="text-red-400 group-hover:text-red-800"><x-svg.delete size="size-5" :filled="$favorite" /></a>
        </div>
        
    </td>
</tr>   