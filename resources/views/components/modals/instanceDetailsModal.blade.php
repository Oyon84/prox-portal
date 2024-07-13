<div class="p-6">
    
    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
        {{ __('This ' . $type . ' is not managed by Prox Portal.') }}
    </h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-5">
        {{ __('Currently this ' . $type . ' is not imported into Prox-Portal and therefor functionality is limited to basic operations. If you want you can import the instance and unlock full functionality, however when imported the instance shoulld not be modified on the proxmox cluster directly.') }}
    </p>
    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Import ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __(' will import the instance into Prox Portal.') }} </p>
        </p>
    </div>

    <div class="flex">
        <p class="mt-1 text-sm font-bold text-gray-600 dark:text-gray-300">
            {{ __('Keep ') }} &nbsp; <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __(' will keep the instance unmanaged, you can still use basic functionality.') }} </p>
        </p>
    </div>

    <div x-show="openImport === 1">
        <x-input-label for="owner" value="{{ __('Owner') }}" class="sr-only" />
        <x-text-input
            id="owner"
            name="owner"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="{{ __('Owner') }}"
        />
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

    <div class="mt-8 flex gap-3 justify-between">
        <div>
            <x-secondary-button class="mr-3" x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button>
                {{ __('Keep') }}
            </x-primary-button>
        </div>
        <div>
            <x-primary-button x-on:click="openImport === 1">
                {{ __('Import') }}
            </x-primary-button>
        </div>
    </div>
</div>