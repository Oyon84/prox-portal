<div class="p-6">
    
    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-5">
        {{ __('Task Details for ' . $uid . ' performed on ' . $node) }}
    </h2>
    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Task ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $this->storedTask->type . ' on ' . $this->storedTask->node->name }} </p>
        </p>
    </div>

    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Stop ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __(' will force a stop on the instance.') }} </p>
        </p>
    </div>
    
    <div class="mt-8 flex gap-3 justify-between">
        <div>
            <x-secondary-button class="mr-3" x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </div>
</div>