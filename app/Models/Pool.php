<?php

namespace App\Models;

use App\Services\ProxmoxAuthService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pool extends Model
{
    use HasFactory;

    public $pools = [];

    public function vms(): HasMany
    {
        return $this->hasMany(Vm::class);
    }

    public function getPools(ProxmoxAuthService $proxmox)
    {        
        $pools = $proxmox->request('/pools');

        foreach ($pools->data as $pool){
            $this->pools[] = $pool;
        }

        $this->pools = collect($this->pools)->sortBy('poolid');

        return $this->pools;
    }

    public function getPoolForInstance(ProxmoxAuthService $proxmox, $vmid)
    {
        dd($vmid);
    }
}
