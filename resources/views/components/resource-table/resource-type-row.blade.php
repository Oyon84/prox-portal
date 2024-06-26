<tr class="bg-gray-100 dark:bg-gray-700">
    <td class="pl-6 py-2 whitespace-nowrap" colspan="1">
        <div class="flex">
            <div class="flex-shrink-0 h-10 w-10">
                <div class="my-2">
                    @if (isset($svg))
                        {{ $svg }}
                    @endif
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap" colspan="4">
        <div class="flex items-center">
            <div class="text-lg font-bold">
                {{ $slot }}
            </div>
        </div>
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <div class="flex items-center">
            <div class="text-xl font-bold">
                <x-primary-button class="text-xs">See all</x-primary-button>
            </div>
        </div>
    </td>
</tr>   