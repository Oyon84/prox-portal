@props([
    'taskType',
    'taskStartTime',
    'taskStopTime',
    'taskUser',
    'taskVmid',
    'taskStatus',
    'index',
])

@php
    if($taskStatus === "OK") {
        $statusClass = "text-green-500 font-bold";
    }
    else {
        $statusClass = "text-red-500 font-bold";
    }
@endphp

<div class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-800 transition-all rounded-md px-2 py-1 my-1">
    <div class="flex justify-between">
        <p class="text-gray-700 dark:text-gray-300 text-sm font-bold">{{ $taskType }}</p><p class="text-xs text-gray-600 dark:text-gray-400">{{ 'Vmid: ' . $taskVmid . ' | ' }} <span class="{{ $statusClass }}">{{ $taskStatus }}</span> </p>
    </div>
    <div class="flex">
        <a href="#" class="text-gray-600 dark:text-gray-400 text-xs">{{'User: ' . $taskUser }}</a>
    </div>
    <div class="flex">
        <p class="text-gray-500 text-xs">{{ $taskStartTime }}</p>
    </div>
</div>