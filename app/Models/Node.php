<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\ProxmoxAuthService;

class Node extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Eloquent relationships

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function vms(): HasMany
    {
        return $this->hasMany(Vm::class);
    }

    public function getAllNodes(ProxmoxAuthService $proxmox)
    {
        $nodes = $proxmox->request('/nodes');
        return $nodes;
    }
}
