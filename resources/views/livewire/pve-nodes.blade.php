<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;
use App\Models\Node;

new class extends Component {
    public $node;

    public $storedNodes;

    public $allVms = [];

    public $allLxcs = [];

    public function mount(ProxmoxAuthService $proxmox)
    {        
        $this->node = $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

        $this->storedNodes = Node::where('available', 1)->get();

        $this->nodes = $this->getNodes($proxmox);

        $this->allVms = $this->getAllVms($proxmox);

        $this->allLxcs = $this->getAllLxcs($proxmox);
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

        //dump($this->allVms);
        
        return $this->allVms;
    }

    public function getAllLxcs($proxmoxAuthInstance)
    {
        $nodes = $this->getNodes($proxmoxAuthInstance);

        foreach ($nodes->data as $key => $node) {
            $lxcs = $proxmoxAuthInstance->request('/nodes/' . $node->node . '/lxc/');
            foreach ($lxcs->data as $key => $lxc) {
                array_push($this->allLxcs, $lxc);
            }
        }

        //dump($this->allLxcs);
        
        return $this->allLxcs;
    }
}; ?>

<div>
    <div class="flex items-center gap-2 pb-3">
        <div>
            <x-svg.chip></x-svg.chip>
        </div>         
        <h1 class="font-bold text-xl">Resources</h1>
    </div>
    <hr class="dark:border-gray-700">

    <x-partials.sidebar-item 
        route="nodes" 
        label="Nodes"
        >
        <x-slot name="svg">
            <x-svg.host></x-svg.host>
        </x-slot>

        <x-slot name="statusSvg">
            <div class="flex bg-gray-200 dark:bg-gray-700 rounded-md px-2">
                <x-svg.tooltip.bolt 
                    size="size-5 text-green-500" 
                    count="{{ count($this->storedNodes) }}"/>
                <x-svg.tooltip.bolt-slash 
                    size="size-5 text-red-500"/>
            </div>
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="vms" 
        label="VMs"
        >
        <x-slot name="svg">
            <x-svg.vm />
        </x-slot>
        <x-slot name="value">
            {{ count($this->allVms) }}
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="containers" 
        label="LXC Containers"
        >
        <x-slot name="svg">
            <x-svg.container />
        </x-slot>
        <x-slot name="value">
            {{ count($this->allLxcs) }}
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="nodes" 
        label="Networks"
        >
        <x-slot name="svg">
            <x-svg.net />
        </x-slot>
        <x-slot name="value">
            {{ __('2') }}
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="nodes" 
        label="Storage"
        >
        <x-slot name="svg">
            <x-svg.disk />
        </x-slot>
        <x-slot name="value">
            {{ __('2') }}
        </x-slot>
    </x-partials.sidebar-item>

    <hr class="dark:border-gray-700">
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.chip></x-svg.chip>
        </div>         
        <h1 class="font-bold text-xl">User Settings</h1>
    </div>
    <hr class="dark:border-gray-700">

    <x-partials.sidebar-item 
        route="profile" 
        label="User"
        >
        <x-slot name="svg">
            <x-svg.user />
        </x-slot>
        <x-slot name="value">
            {{ Auth::user()->pveUsername . '@pve' }}
        </x-slot>
    </x-partials.sidebar-item>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.permissions></x-svg.permissions>
        </div>
        <h1 class="font-bold">Permissions:</h1>
        {{ 'pool admin' }}
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.task></x-svg.task>
        </div>
        <h1 class="font-bold">Tasks:</h1>
        {{ 'Create VM - 3h ago' }}
    </div>
    <hr class="dark:border-gray-700">
    <div class="flex items-center gap-2 py-3">
        <x-primary-button class="mt-2 w-full"><x-svg.vm size="size-5 mr-2" />{{ 'Create VM' }}</x-primary-button>
    </div>
    <div class="flex items-center gap-2 py-3">
        <x-primary-button class="mb-2 w-full"><x-svg.container size="size-5 mr-2" />Create Container</x-primary-button>
    </div>
</div>
