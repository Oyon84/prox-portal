<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;

new class extends Component {

    public $pools = [];

    public function mount(ProxmoxAuthService $proxmox)
    {
        $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

        $pools = $proxmox->request('/pools');

        foreach ($pools->data as $key => $pool)
        {
            array_push($this->pools, $pool);
        }
    }
    //
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Resource Pool Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Current resource pools you are member of:") }}
        </p>
    </header>

    <div class="mt-4">
        <x-input-label for="pools" :value="__('Resource Pools')" />
        <select name="pools" id="pools" size="5" style="overflow-y: auto;"
            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            @foreach ($this->pools as $pool)
                <option value="{{ $pool->poolid }}">{{ $pool->poolid }} - {{ $pool->comment }}</option>
            @endforeach
        </select>
    </div>
</section>
