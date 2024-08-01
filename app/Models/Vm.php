<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ProxmoxAuthService;
use App\Models\Node;

class Vm extends Model
{
    use HasFactory;

    public $allVms = [];

    public function __construct()
    {
        $this->nodes = new Node;
    }

    public function getVm($vmid)
    {
        dd($vmid);
    }

    public function getAllVms(ProxmoxAuthService $proxmox)
    {
        $nodes = $this->nodes->getAllNodes($proxmox);
        
        foreach ($nodes->data as $node) {
            $vms = $proxmox->request('/nodes/' . $node->node . '/qemu/', ['full' => true]);
            foreach ($vms->data as $vm) {
                $vm->node = $node->node;
                $this->allVms[] = $vm;
            }
        }

        return collect($this->allVms)->sortBy('name');
    }
}
