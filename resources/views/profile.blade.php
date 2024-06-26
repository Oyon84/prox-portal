<x-layouts.app>
    <x-slot name="header">
        <x-partials.header headerText="{{ __(Auth::user()->lastName) }},  {{ __(Auth::user()->firstName) }}" svg="user"/>                         
    </x-slot>

    <div class="py-12">
        <div class="mx-5 lg:grid lg:grid-cols-3 xl:grid-cols-5 gap-8 sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg col-span-4">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="lg:flex gap-4 justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="mb-4 lg:grow max-w-full">
                    <livewire:profile.proxmox-information />
                </div>
                <div class="mb-2 sm:grow max-w-full">
                    <livewire:profile.proxmox-user-information />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
