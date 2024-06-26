<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;

new class extends Component {

    public $users = [];

    public function mount(ProxmoxAuthService $proxmox)
    {        
        $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

        $users = $proxmox->request('/access/users/');

        foreach ($users->data as $key => $user)
        {
            array_push($this->users, $user);
        }
    }
    //
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Proxmox User information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("View details on your user account in Proxmox PVE authentication") }}
        </p>
    </header>

    <div class="mt-4">
        <x-input-label for="pools" :value="__('Comment')" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $this->users[0]->comment }}
        </p>
        <x-input-label for="pools" :value="__('Realm type')" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ 'PVE' }}
        </p>
        <x-input-label for="pools" :value="__('Enabled')" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if ($this->users[0]->enable === 1)
                Account is active
            @else
                Account is disabled
            @endif
        </p>
    </div>
</section>
