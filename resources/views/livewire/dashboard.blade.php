<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;
use Illuminate\Support\Arr;

new class extends Component 
{    
    public $allVms = [];

    public $allLxcs = [];

    public function mount(ProxmoxAuthService $proxmox)
    {        
        $this->node = $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

        $this->allVms = $this->getAllVms($proxmox);

        $this->allLxcs = $this->getAllLxcs($proxmox);
    }

    public function setUptime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds %60;

        return $hours > 0 ? "$hours hours, $minutes minutes" : ($minutes > 0 ? "$minutes minutes, $seconds seconds" : "$seconds seconds");
    }

    public function getNodes($proxmoxAuthInstance)
    {
        return $proxmoxAuthInstance->request('/nodes');
    }

    public function getAllVms($proxmoxAuthInstance)
    {
        $nodes = $this->getNodes($proxmoxAuthInstance);

        foreach ($nodes->data as $key => $node) {
            $vms = $proxmoxAuthInstance->request('/nodes/' . $node->node . '/qemu/', ['full' => true]);
            foreach ($vms->data as $key => $vm) {
                array_push($this->allVms, $vm);
            }
        }
        
        return collect($this->allVms)->sortBy('name');
    }

    public function getAllLxcs($proxmoxAuthInstance)
    {
        $nodes = $this->getNodes($proxmoxAuthInstance);

        foreach ($nodes->data as $key => $node) {
            $lxcs = $proxmoxAuthInstance->request('/nodes/' . $node->node . '/lxc/');
            foreach ($lxcs->data as $key => $lxc) {
                $interfaces = $proxmoxAuthInstance->request('/nodes/' . $node->node . '/lxc/' . $lxc->vmid . '/interfaces/')->data;
                foreach($interfaces as $interface)
                {
                    if ($interface->name == 'eth0')
                    {
                        $lxc->interface = $interface->name;
                        $lxc->ip = $interface->inet;
                    }
                }
                
                array_push($this->allLxcs, $lxc);
            }
        }

        //dump($this->allLxcs);
        
        return collect($this->allLxcs)->sortBy('name');
    }
}; ?>

<div class="h-full">
    <x-slot name="header">
        <x-partials.header headerText="Dashboard" svg="dashboard"></x-partials.header>
    </x-slot>

    <div class="mx-5 h-full lg:grid lg:grid-cols-3 xl:grid-cols-5 gap-8 sm:px-6 lg:px-8 grid-flow-row-dense">
        <div class="h-full pt-6 lg:col-span-2 xl:col-span-4">
            <div class="h-full mx-auto">
                <x-partials.resource-explorer 
                :vmData="$allVms" 
                :lxcData="$allLxcs" 
                />
            </div>
        </div>
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
    </div>
</div>
