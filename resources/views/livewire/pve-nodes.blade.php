<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Services\ProxmoxAuthService;
use App\Services\modalService;
use App\Models\Node;
use App\Models\Task;
use App\Models\User;
use App\Models\Vm;
use App\Models\Lxc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public $proxmox;
    public $tasks;
    public $tasksNumber = 5;
    public $storedNodes;
    public $storedTask;
    public $allVms = [];
    public $allLxcs = [];
    public $taskData = [];

    public function mount(ProxmoxAuthService $proxmox, Vm $vm, Lxc $lxc, Node $node)
    {        
        $this->proxmox = $proxmox->authenticate(Auth::user()->pveUsername, decrypt(Session::get('pve_password')),'pve');
        $this->storedNodes = $node->where('available', 1)->get();
        $this->nodes = $node->getAllNodes($proxmox);
        $this->allVms = $vm->getAllVms($proxmox);
        $this->allLxcs = $lxc->getAllLxcs($proxmox);
        $this->tasks = $this->getTasks($proxmox)->data;
    }

    protected function initializeProxmoxInstance(ProxmoxAuthService $proxmox){
        
        $instance = $proxmox->authenticate(Auth::user()->pveUsername, decrypt(Session::get('pve_password')),'pve');

        return $instance;

    }

    public function openTaskModal($modalName, $uid, $node, $type, $name, Task $task)
    {
        $this->storedTask = $task->getTaskByUid($uid) ?? $this->storeAndGetTasks($uid, $task);
        $this->taskData = [
            'uid' => $uid,
            'node' => $node,
            'type' => $type,
            'name' => $name,
            'vmid' => $this->storedTask->vmid,
            'status' => $this->storedTask->status,
            'user' => $this->storedTask->user->pveUsername,
        ];
      
        $this->dispatch('open-modal', $modalName);
    }

    public function getUid($upid)
    {
        $uid = explode(':', $upid);
        $uid = $uid[2] . ":" . $uid[3] . ":" . $uid[4];
        
        return $uid;
    }

    public function refreshTasks()
    {
        $this->reset('tasks');
    }

    public function setNumberTasks($number)
    {
        Session::put('taskNumber', $number);
        $this->tasksNumber = $number;
    }

    public function covertEpochTime($epoch)
    {
        return date("F j, Y, g:i a", $epoch);
    }

    public function getTasks($proxmoxAuthInstance)
    {
        $tasks = $proxmoxAuthInstance->getCurrentTasks();

        return $tasks;
    }

    public function storeTasks()
    {   
        $tasks = new Task;
        
        $tasks->storeTasks($this->tasks);
    }

    public function storeAndGetTasks($uid, Task $tasks)
    {
        $tasks->storeTasks($this->tasks);

        return $this->storedTask = $tasks->getTaskByUid($uid);
    }
    
}; ?>
<div>
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
            <div>
                <span class="text-xs text-gray-500">
                    <x-partials.task-number
                        :active="$this->tasksNumber === 5"
                        number="5" /> | 
                        <x-partials.task-number
                        :active="$this->tasksNumber === 10"
                        number="10" /> | 
                        <x-partials.task-number
                        :active="$this->tasksNumber === 20"
                        number="20" />               
                </span>
            </div>
            <div class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-all">
                <x-svg.refresh size="size-5 mr-1"/>
            </div>
            
        </div>
        <!-- <div wire:poll.5000ms='refreshTasks()'> -->
            <div class="h-80 mb-2 overflow-auto no-scrollbar">
            @if($this->tasks == null)
                <p class="text-sm text-gray-500 text-center">No tasks</p>
            @endif
            @foreach ($this->tasks as $key => $task)
                @if ($loop->index < $this->tasksNumber)
                    <x-partials.taskItem
                        index="{{ $loop->index }}"
                        taskType="{{ $task->type }}"
                        taskStartTime="{{ $this->covertEpochTime($task->starttime) }}"
                        taskVmid="{{$task->id}}"
                        taskStatus="{{ $task->status }}"
                        taskUser="{{ $task->user }}"
                        uid="{{ $this->getUid($task->upid) }}"
                        node="{{ $task->node }}"
                    />
                @endif
            @endforeach
        </div>
        <hr class="dark:border-gray-700">
        <div class="flex items-center gap-2 py-1">
            <x-primary-button class="my-1 w-full" wire:click='storeTasks()'><x-svg.task size="size-5 mr-2" />Show all tasks</x-primary-button>
        </div>
    </div>
    
    <x-modal name="taskDetail" :show="$errors->isNotEmpty()" focusable >
        @isset($this->taskData['uid'])
            @php
                extract($this->taskData);
            @endphp
            <div class="flex justify-between items-center bg-brand-dark dark:bg-brand-light shadow-md dark:shadow-gray-700 p-6">
                <div>
                    <p class="text-gray-100 dark:text-gray-800">Task details</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">UID: {{ $uid }}</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">Node: {{ $node }}</p>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">Type: {{ $type }}</p>
                </div>
                <div>
                    <x-modals.partials.headerNameSvg
                    name="{{'VMID: ' . $vmid}}"
                    uid="{{$uid}}"
                    value1="{{$user}}"
                    type="task" 
                    value2="{{$status}}"
                    label1="User: "
                    label2="Status: "
                    /> 
                </div>
            </div>
            <x-modals.taskDetailModal 
                uid="{{$uid}}" 
                node="{{$node}}" 
                type="{{$type}}" 
                name="{{$type}}"
                />
        @endisset
    </x-modal>
</div>
