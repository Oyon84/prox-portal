<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Services\ProxmoxAuthService;
use App\Models\Node;

new class extends Component {
    public $node;

    public $tasks;

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

        $this->tasks = $this->getTasks($proxmox)->data;
    }

    protected function initializeProxmoxInstance(ProxmoxAuthService $proxmox){
        
        $instance = $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

        return $instance;

    }

    public function refreshTasks()
    {
        $this->reset('tasks');
    }

    public function covertEpochTime($epoch)
    {
        return date("F j, Y, g:i a", $epoch);
    }

    public function getNodes($proxmoxAuthInstance)
    {
        return $proxmoxAuthInstance->request('/nodes');
    }

    public function getTasks($proxmoxAuthInstance)
    {
        $tasks = $proxmoxAuthInstance->getCurrentTasks();

        return $tasks;

        //dd($this->tasks->data[0]);
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
        
        return $this->allLxcs;
    }
}; ?>

<div>
    <x-partials.sidebar-item 
        route="dashboard" 
        label="Dashboard"
        :active="request()->routeIs('dashboard')"
        >
        <x-slot name="svg">
            <x-svg.dashboard></x-svg.diashboard>
        </x-slot>

        <x-slot name="value">
            FHS7 ICT
        </x-slot>
    </x-partials.sidebar-item>
    <hr class="dark:border-gray-700 mb-2">

    <x-partials.sidebar-item 
        route="nodes" 
        label="Nodes"
        :active="request()->routeIs('nodes')"
        >
        <x-slot name="svg">
            <x-svg.host></x-svg.host>
        </x-slot>

        <x-slot name="statusSvg">
            <div class="flex bg-gray-200 dark:bg-gray-700 rounded-md px-2">
                <x-svg.tooltip.bolt 
                    size="size-5 text-green-400" 
                    count="{{ count($this->storedNodes) }}"/>
                <x-svg.tooltip.bolt-slash 
                    size="size-5 text-red-400"/>
            </div>
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="vms" 
        label="VMs"
        :active="request()->routeIs('vms')"
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
        :active="request()->routeIs('containers')"
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
        :active="request()->routeIs('nodes')"
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

    <hr class="dark:border-gray-700 mb-2">

    <x-partials.sidebar-item 
        route="profile" 
        label="User"
        :active="request()->routeIs('profile')"
        >
        <x-slot name="svg">
            <x-svg.user />
        </x-slot>
        <x-slot name="value">
            {{ Auth::user()->pveUsername . '@pve' }}
        </x-slot>
    </x-partials.sidebar-item>

    <x-partials.sidebar-item 
        route="pools" 
        label="Pools"
        >
        <x-slot name="svg">
            <x-svg.pool />
        </x-slot>
        <x-slot name="value">
            {{ '2' }}
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
    <div class="flex items-center gap-2 py-1">
        <x-primary-button class="my-1 w-full"><x-svg.vm size="size-5 mr-2" />{{ 'Create VM' }}</x-primary-button>
    </div>
    <div class="flex items-center gap-2 py-1">
        <x-primary-button class="my-1 w-full" wire:click='getTasks()'><x-svg.container size="size-5 mr-2" />Create Container</x-primary-button>
    </div>
    <div class="flex items-center gap-2 py-1">
        <x-primary-button class="my-1 w-full" wire:click='getTasks()'><x-svg.container size="size-5 mr-2" />Create Storage</x-primary-button>
    </div>
    <div class="flex items-center gap-2 py-1">
        <x-primary-button class="my-1 w-full" wire:click='getTasks()'><x-svg.net size="size-5 mr-2" />Create Network</x-primary-button>
    </div>
    <hr class="dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 py-3">
            <x-svg.task></x-svg.task>
            <h1 class="font-bold">Recent Tasks</h1>
        </div>
        <div class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-all">
            <x-svg.refresh size="size-5 mr-1"/>
        </div>
        
    </div>
    <!-- <div wire:poll.5000ms='refreshTasks()'> -->
        <div class="max-h-80 overflow-auto no-scrollbar">
        @foreach ($this->tasks as $key => $task)
            @if ($loop->index < 7)
                <x-partials.taskItem
                    index="{{ $loop->index }}"
                    taskType="{{ $task->type }}"
                    taskStartTime="{{ $this->covertEpochTime($task->starttime) }}"
                    taskVmid="{{$task->id}}"
                    taskStatus="{{ $task->status }}"
                    taskUser="{{ $task->user }}"
                />
            @endif
        @endforeach
    </div>
    <hr class="dark:border-gray-700">
    <div class="flex items-center gap-2 py-1">
        <x-primary-button class="my-1 w-full" wire:click='refreshTasks()'><x-svg.task size="size-5 mr-2" />Show all tasks</x-primary-button>
    </div>
</div>
