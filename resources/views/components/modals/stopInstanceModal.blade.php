<div class="p-6">
    
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">
        {{ __('Are you sure you want stop ' . $this->instanceData['type'] . ' ' . $this->instanceData['vmid']) }}
    </h2>
    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Shutdown ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __(' will stop the instance gracefully.') }} </p>
        </p>
    </div>

    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Stop ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __(' will force a stop on the instance.') }} </p>
        </p>
    </div>

    {{-- <div class="mt-6">
        <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

        <x-text-input
            wire:model="password"
            id="password"
            name="password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="{{ __('Password') }}"
        />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div> --}}

    <div class="mt-6 flex gap-3 justify-start">
        <x-secondary-button x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-primary-button wire:click="shutdownInstance('confirmStopInstance', '{{ $vmid }}', '{{ $node}}', '{{ $type }}')">
            {{ __('Shutdown') }}
        </x-primary-button>

        <x-danger-button wire:click="stopInstance('confirmStopInstance', '{{ $vmid }}', '{{ $node}}', '{{ $type }}')">
            {{ __('Stop') }}
        </x-danger-button>
    </div>
</div>