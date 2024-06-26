<?php

use Livewire\Volt\Component;
use App\Services\ProxmoxAuthService;

new class extends Component {
    public $node;

    public $allVms = [];

    public $allLxcs = [];

    public function mount(ProxmoxAuthService $proxmox)
    {        
        $this->node = $proxmox->authenticate(Auth::user()->pveUsername,'Nortel01','pve');

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
        <h1 class="font-bold text-xl">Summery Stats</h1>
    </div>
    <hr class="dark:border-gray-700">
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.host></x-svg.host>
        </div>
        <h1 class="font-bold">Nodes:</h1>
        {{ count($this->nodes->data) }}
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.vm></x-svg.vm>
        </div>
        <h1 class="font-bold">VMs:</h1>
        {{ count($this->allVms) }}
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.container></x-svg.container>
        </div>
        <h1 class="font-bold">LXCs:</h1>
        {{ count($this->allLxcs) }}
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.net></x-svg.net>
        </div>
        <h1 class="font-bold">Networks:</h1>
        {{ 2 }}
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.disk></x-svg.disk>
        </div>
        <h1 class="font-bold">Disks:</h1>
        {{ 5 }}
    </div>
    <hr class="dark:border-gray-700">
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.user></x-svg.user>
        </div>
        <h1 class="font-bold">User:</h1>
        {{ 'cverscho@pve' }}
    </div>
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
        <div>
            <x-svg.vm></x-svg.vm>
        </div>
        <x-primary-button class="my-2">Create VM</x-primary-button>
    </div>
    <div class="flex items-center gap-2 py-3">
        <div>
            <x-svg.container></x-svg.container>
        </div>
        <x-primary-button class="my-2">Create Container</x-primary-button>
    </div>
</div>
