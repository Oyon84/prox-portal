<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ProxmoxAuthService;
use App\Models\Node;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vm extends Model
{
    use HasFactory;

    public $allVms = [];

    public function __construct()
    {
        $this->nodes = new Node;
    }

    // Eloquent relationships
    
    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function pool(): BelongsTo
    {
        return $this->belongsTo(Pool::class);
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
