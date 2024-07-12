<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;
use Illuminate\Support\Arr;

new class extends Component 
{    
    protected $proxmox;

    public $nodes;
    
    public $allVms = [];

    public $allLxcs = [];

    // Dependency injection via mount method
    public function mount(ProxmoxAuthService $proxmox)
    {
        $this->initializeProxmox($proxmox);
    }

    protected function initializeProxmox(ProxmoxAuthService $proxmox)
    {
        $this->proxmox = $proxmox;

        // Authenticate the Proxmox service
        $this->proxmox->authenticate(Auth::user()->pveUsername, 'Nortel01', 'pve');

        // Reset data to avoid duplication
        $this->nodes = [];
        $this->allVms = [];
        $this->allLxcs = [];
        
        // Fetch Nodes data
        $this->nodes = $this->getNodes();

        // Fetch VM and LXC data
        $this->allVms = $this->getAllVms();
        $this->allLxcs = $this->getAllLxcs();
    }

    protected function ensureProxmoxInitialized()
    {
        if (is_null($this->proxmox)) {
            // Reinitialize if needed
            $this->initializeProxmox(app(ProxmoxAuthService::class));
        }
    }

    public function refresh()
    {
        $this->initializeProxmox(app(ProxmoxAuthService::class));
    }

    // Ensure $proxmox is not null before use
    public function startInstance($vmid, $node, $type)
    {                
        $this->ensureProxmoxInitialized();
        switch ($type) {
            case 'vm':
                $this->proxmox->startVM([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
            
            default:
                $this->proxmox->startLXC([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
        }
    }

    public function stopInstance($vmid, $node, $type)
    {
        $this->ensureProxmoxInitialized();
        switch ($type) {
            case 'vm':
                $this->proxmox->stopVM([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
            
            default:
                $this->proxmox->stopLXC([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
        }
        
    }

    public function setUptime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds %60;

        return $hours > 0 ? "$hours hours, $minutes minutes" : ($minutes > 0 ? "$minutes minutes, $seconds seconds" : "$seconds seconds");
    }

    public function getNodes()
    {        
        return $this->proxmox->request('/nodes');
    }

    public function getAllVms()
    {        
        foreach ($this->nodes->data as $node) {
            $vms = $this->proxmox->request('/nodes/' . $node->node . '/qemu/', ['full' => true]);
            foreach ($vms->data as $vm) {
                $vm->node = $node->node;
                $this->allVms[] = $vm;
            }
        }
        
        return collect($this->allVms)->sortBy('name');
    }

    public function getAllLxcs()
    {
        foreach ($this->nodes->data as $node) {
            $lxcs = $this->proxmox->request('/nodes/' . $node->node . '/lxc/');
            foreach ($lxcs->data as $lxc) {
                $lxc->node = $node->node;
                $interfaces = $this->proxmox->request('/nodes/' . $node->node . '/lxc/' . $lxc->vmid . '/interfaces/')->data;
                if ($interfaces) {
                    foreach ($interfaces as $interface) {
                        if ($interface->name == 'eth0') {
                            $lxc->interface = $interface->name;
                            $lxc->ip = $interface->inet;
                        }
                    }
                } else {
                    $lxc->interface = 'N/A';
                    $lxc->ip = 'n/a';
                }
                
                $this->allLxcs[] = $lxc;
            }
        }

        return collect($this->allLxcs)->sortBy('name');
    }

}; ?>

<div class="h-full">
    <x-slot name="header">
        <x-partials.header headerText="Dashboard" svg="dashboard"></x-partials.header>
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
            <div wire:poll.5000ms='refresh' class="h-full mx-auto">
                <x-partials.resource-explorer 
                :vmData="$allVms" 
                :lxcData="$allLxcs" 
                />
            </div>
        </div>
        <x-modal name="confirmStopInstance" :show="$errors->isNotEmpty()" focusable>
            <form wire:submit="stopInstance" class="p-6">
    
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">
                    {{ __('Are you sure you want stop the VM') }}
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

                    <x-primary-button x-on:click="$dispatch('close')">
                        {{ __('Shutdown') }}
                    </x-primary-button>
    
                    <x-danger-button>
                        {{ __('Stop') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</div>
