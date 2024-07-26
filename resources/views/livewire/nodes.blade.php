<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="mx-5 h-full">
        <div class="pt-6">
            <div class="max-w-5xl m-auto">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('No nodes were found in the database, you can import nodes by running the following Artisan command on the console:')}}
                        <div class="bg-gray-200 dark:bg-gray-600 p-3 m-2 rounded-md shadow-sm border-4 border-gray-50">
                            <p class="my-1 text-gray-500 dark:text-gray-300">php artisan nodes:update</p>
                        </div>
                        <p class="mb-5">Follow the instructions and once nodes have been imported refresh this page</p>
                        <x-primary-button x-on:click="$refresh()">Refresh</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
