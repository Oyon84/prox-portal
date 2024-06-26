@props([
    'cell1',
    'cell2',
    'cell3',
    'cell4',
    'cell5',
    'cell6',
    ])

<tr>
    @isset($cell1)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell1 }}
        </th>
    @endisset
    @isset($cell2)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell2 }}
        </th>
    @endisset
    @isset($cell3)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell3 }}
        </th>
    @endisset
    @isset($cell4)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell4 }}
        </th>
    @endisset
    @isset($cell5)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell5 }}
        </th>
    @endisset
    @isset($cell6)
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
            {{ $cell6}}
        </th>
    @endisset
</tr>