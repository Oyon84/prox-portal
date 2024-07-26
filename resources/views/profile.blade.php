<x-layouts.app>
    <x-slot name="header">
        <x-partials.header headerText="Profile: {{ __(Auth::user()->lastName) }},  {{ __(Auth::user()->firstName) }}" svg="user"/>                         
    </x-slot>

    <div class="h-full">
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
            <div class="h-full pt-6 lg:col-span-2 xl:col-span-2">
                <div wire:poll.5000ms class="h-full mx-auto">
                    <div class="p-4 sm:p-8 mb-5 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-full pt-6 lg:col-span-2 xl:col-span-2">
                <div class="lg:flex gap-4 justify-between p-4 mb-5 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="mb-4 lg:grow max-w-full">
                        <livewire:profile.proxmox-user-information />
                    </div>
                </div>
                <div class="lg:flex gap-4 justify-between p-4 mb-5 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="mb-2 sm:grow max-w-full">
                        <livewire:profile.proxmox-information />
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-5 lg:grid lg:grid-cols-3 xl:grid-cols-5 gap-8 sm:px-6 lg:px-8">
            <div class="lg:flex gap-4 justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="mb-4 lg:grow max-w-full">
                    <livewire:profile.proxmox-information />
                </div>
                <div class="mb-2 sm:grow max-w-full">
                    <livewire:profile.proxmox-user-information />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
