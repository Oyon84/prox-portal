<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Node;
use App\Models\User;
use App\Models\Vm;

new class extends Component 
{    
    protected $proxmox;
    public $nodeCheck;
    public $pveUsername;
    public $nodes;
    public $vm;
    public $instanceData = [];
    public $allVms = [];
    public $allLxcs = [];
    public $pools = [];

    // Dependency injection via mount method
    public function mount(ProxmoxAuthService $proxmox, Node $node, Vm $vm)
    {        
        $this->nodeCheck = $node->first() ? true : false;
        $this->nodes = $node;
        $this->vm = $vm;
        $this->pveUsername = Auth::user()->pveUsername;
        $this->initializeProxmox($proxmox, $node);
    }

    protected function initializeProxmox(ProxmoxAuthService $proxmox, Node $node)
    {
        $this->proxmox = $proxmox;
        $vm = $this->vm;

        // Authenticate the Proxmox service
        $this->proxmox->authenticate($this->pveUsername, decrypt(Session::get('pve_password')), 'pve');

        // Reset data to avoid duplication
        // $this->nodes = [];
        $this->allVms = [];
        $this->allLxcs = [];
        $this->pools = [];
        
        // Fetch Nodes data
        $this->nodes = $node->getAllNodes($this->proxmox);

        // Fetch Pool data
        $this->pools = $this->getPools();

        // Fetch VM and LXC data
        $this->allVms = $vm->getAllVms($this->proxmox);
        $this->allLxcs = $this->getAllLxcs();
    }

    protected function resetProxmoxInstance(ProxmoxAuthService $proxmox)
    {
        $this->proxmox = $proxmox;

        // Authenticate the Proxmox service
        $this->proxmox->authenticate($this->pveUsername, decrypt(Session::get('pve_password')), 'pve');
        
        $this->allVms = [];
        $this->allLxcs = [];

        // Fetch VM and LXC data
        $this->allVms = $this->vm->getAllVms($this->proxmox);
        $this->allLxcs = $this->getAllLxcs();
    }

    protected function ensureProxmoxInitialized()
    {
        if (is_null($this->proxmox)) {
            // Reinitialize if needed
            $this->resetProxmoxInstance(app(ProxmoxAuthService::class));
        }
    }

    public function refresh()
    {
        $this->resetProxmoxInstance(app(ProxmoxAuthService::class));
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

    public function openModal($modalName, $vmid, $node, $type, $name)
    {
        $this->instanceData = [
            'vmid' => $vmid,
            'node' => $node,
            'type' => $type,
            'name' => $name,
        ];
        
        $this->dispatch('open-modal', $modalName);
    }

    public function stopInstance($modalName, $vmid, $node, $type)
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
        
        $this->dispatch('close-modal', $modalName);
    }

    public function shutdownInstance($modalName, $vmid, $node, $type)
    {
        $this->ensureProxmoxInitialized();
        switch ($type) {
            case 'vm':
                $this->proxmox->shutdownVM([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
            
            default:
                $this->proxmox->shutdownLXC([
                    'vmid' => $vmid,
                    'node' => $node,
                    'type' => $type,
                ]);
                break;
        }   
        
        $this->dispatch('close-modal', $modalName);
    }

    public function setUptime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds %60;

        return $hours > 0 ? "$hours hours, $minutes minutes" : ($minutes > 0 ? "$minutes minutes, $seconds seconds" : "$seconds seconds");
    }

    public function getPools()
    {
        $returnData = [];
        
        $pools = $this->proxmox->request('/pools');

        foreach ($pools->data as $key => $pool) {

            array_push($returnData, $pool);

        }

        return $returnData;
    }

    public function getInstancePool($vmid)
    {
        $poolData = $this->getPools();

        dd($poolData);


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
    @if ($this->nodeCheck === false)
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
    @else
        <div class="mx-5 h-full lg:grid lg:grid-cols-3 xl:grid-cols-5 gap-5 grid-flow-row-dense">
            <div class="pt-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            @livewire('pve-nodes')
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
            <x-modal name="confirmStopInstance" maxWidth='lg' :show="$errors->isNotEmpty()" focusable>
                @isset($this->instanceData['vmid'])
                    @php
                        extract($this->instanceData);
                    @endphp
                    <div class="flex justify-between items-center bg-brand-dark dark:bg-brand-light shadow-md p-6">
                        <div>
                            <p class="text-gray-100 dark:text-gray-800">Instance details</p>
                            <p class="text-gray-300 dark:text-gray-500 text-xs">VMID: {{ $vmid }}</p>
                            <p class="text-gray-300 dark:text-gray-500 text-xs">Node: {{ $node }}</p>
                            <p class="text-gray-300 dark:text-gray-500 text-xs">Type: {{ $type }}</p>
                        </div>
                        <x-modals.partials.headerNameSvg
                            name="{{$name}}"
                            vmid="{{$vmid}}"
                            value1="{{$node}}"
                            type="{{$type}}"
                            value2="OK" 
                            label1="Running on: "
                            label2="Status: "
                            />
                    </div>
                    <x-modals.stopInstanceModal vmid="{{$vmid}}" node="{{$node}}" type="{{$type}}"/>
                @endisset
            </x-modal>

            <x-modal name="instanceDetails" :show="$errors->isNotEmpty()" focusable >
                @isset($this->instanceData['vmid'])
                    @php
                        extract($this->instanceData);
                    @endphp
                    <div class="flex justify-between items-center bg-brand-dark dark:bg-brand-light shadow-md dark:shadow-gray-700 p-6">
                        <div>
                            <p class="text-gray-100 dark:text-gray-800">Instance details</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs">VMID: {{ $vmid }}</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs">Node: {{ $node }}</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs">Type: {{ $type }}</p>
                        </div>
                        <div>
                            <x-modals.partials.headerNameSvg
                            name="{{$name}}"
                            vmid="{{$vmid}}"
                            value1="{{$node}}"
                            type="{{$type}}"
                            value2="OK" 
                            label1="Running on: "
                            label2="Status: " 
                            /> 
                        </div>
                    </div>
                    <x-modals.instanceDetailsModal 
                        vmid="{{$vmid}}" 
                        node="{{$node}}" 
                        type="{{$type}}" 
                        name="{{$name}}"
                        />
                @endisset
            </x-modal>
        </div>
    @endif    
</div>
