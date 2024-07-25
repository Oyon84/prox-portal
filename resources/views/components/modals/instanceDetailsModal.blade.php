<div x-data="{open: false}" class="p-6">
    <div x-show="!open" x-transition:enter.delay.500ms>
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ __('This ' . strtoupper($type) . ' is not managed by Prox Portal.') }}
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
    </div>

    <div x-show="open" class="mb-5" x-transition:enter>
        <h2 class="text-lg font-bold mb-5 text-gray-900 dark:text-gray-100">
            {{ __('Import ' . $type . ' to Prox Portal.') }} <span class=" ml-3 text-xs text-gray-500">Check and provide details.</span>
        </h2>

        <x-input-label for="name" value="{{ __('Name') }}" />
        <div class="flex justify-between items-center">
            <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 mx-5 block w-3/4"
            value="{{ $name }}"
            />
        </div>

        <x-input-label for="owner" value="{{ __('Owner') }}" class="mt-3"/>
        <div class="flex justify-between items-center">
            <x-text-input
            id="owner"
            name="owner"
            type="text"
            class="mt-1 mx-5 block w-3/4"
            value="{{ Auth::user()->pveUsername }}"
            />
            <div class="group relative">
                <x-primary-button>
                    <x-svg.user size="size-5" />
                </x-primary-button>
                <span class="group-hover:opacity-100 whitespace-nowrap transition-opacity delay-300 dark:bg-gray-100 bg-gray-800 px-1 text-xs dark:text-gray-800 text-gray-100 rounded-sm absolute left-1/2 -translate-x-1/2 translate-y-full opacity-0 m-4 mx-auto">Find User</span>
            </div>
        </div>

        <x-input-label for="pool" value="{{ __('Resource Pool') }}" class="mt-3"/>
        <select name="pools" id="pools" size="5" style="overflow-y: auto;"
            class="block mt-1 mx-5 w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            @foreach ($this->pools as $pool)
                <option class="font-bold" value="{{ $pool->poolid }}">{{ $pool->poolid }} - {{ $pool->comment }}</option>
            @endforeach
        </select>
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
            <x-secondary-button class="mr-3" x-on:click="$dispatch('close'); open = false" >
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button>
                {{ __('Keep') }}
            </x-primary-button>
        </div>
        <div class="flex gap-3">
            <x-primary-button x-on:click="open ? $dispatch('close') : open = ! open" x-text="open ? 'Back' : 'Import'">
                {{ __('Import') }}
            </x-primary-button>
            <x-secondary-button x-on:click="alert('Importing the VM into the database...')" x-bind:class="open ? '' : 'hidden'">
                {{ __('Import') }}
            </x-secondary-button>
        </div>
    </div>
</div>