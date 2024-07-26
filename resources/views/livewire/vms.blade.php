<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="h-full">
        <x-slot name="header">
            <x-partials.header headerText="VMs" svg="vms"></x-partials.header>
        </x-slot>
        <div class="mx-5 h-full lg:grid lg:grid-cols-3 xl:grid-cols-5 gap-5 grid-flow-row-dense">
            <div class="pt-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            @livewire('pve-nodes')
                        </div>
                    </div>
                    <div class="h-full mt-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center gap-2 pb-3">
                            <div>
                                <x-svg.chip></x-svg.chip>
                            </div>         
                                <h1 class="font-bold text-xl">Task History</h1>
                            </div>
                            <hr class="dark:border-gray-700">
                            <div class="flex items-center gap-2 py-3">
                                <div>
                                    <x-svg.host></x-svg.host>
                                </div>
                                <h1 class="font-bold">Nodes:</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-full pt-6 lg:col-span-2 xl:col-span-4">
                <div wire:poll.5000ms class="h-full mx-auto">
                    <x-partials.vm-explorer 
                        
                    />
                </div>
            </div>
        </div>
    </div>
</div>
