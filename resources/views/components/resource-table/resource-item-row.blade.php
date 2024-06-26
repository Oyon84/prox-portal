@props([
    'name',
    'nameSub',
    'details',
    'detailsSub',
    'status',
    'ip',
    'favorite',
    ])

<tr class="">
    <td class="pl-9 py-1 whitespace-nowrap">
        <div class="flex items-center h-10 w-10">
            {{ $svg }}
        </div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <div class="flex items-center">
            <div>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                    {{ $name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ $nameSub }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <div class="text-sm text-gray-900 dark:text-gray-200">{{ $details }}</div>
        <div class="text-xs text-gray-500">{{ $detailsSub }}</div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-md {{ $status == 'Stopped' ? "bg-red-200 text-red-800 ring-1 ring-red-300" : "bg-green-200 text-green-800 ring-1 ring-green-300" }} ">
            {{ $status }}
        </span>
    </td>
    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
        {{ $ip }}
    </td>
    <td class="flex items-center justify-between px-6 py-3 whitespace-nowrap  text-sm font-medium">
        <a href="#" class=""><x-svg.favorite /></a>
        @if ($status == 'Stopped')
            <a href="#" class="text-green-600 hover:text-green-900"><x-svg.play /></a>
        @else
            <a href="#" class="text-red-600 hover:text-red-900"><x-svg.stop /></a>
        @endif
        <a href="#" class=""><x-svg.edit size="size-5" /></a>
        <a href="#" class="text-red-600 hover:text-red-900"><x-svg.delete size="size-5" :filled="$favorite" /></a>
    </td>
</tr>   