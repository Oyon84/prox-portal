<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ProxmoxAuthService;
use App\Models\Node;

class Lxc extends Model
{
    use HasFactory;

    public $allLxcs = [];

    public function __construct()
    {
        $this->nodes = new Node;
    }

    public function getLxc($vmid)
    {
        dd($vmid);
    }

    public function getAllLxcs(ProxmoxAuthService $proxmox)
    {
        $nodes = $this->nodes->getAllNodes($proxmox);

        foreach ($nodes->data as $node) {
            $lxcs = $proxmox->request('/nodes/' . $node->node . '/lxc/');
            foreach ($lxcs->data as $lxc) {
                $lxc->node = $node->node;
                $interfaces = $proxmox->request('/nodes/' . $node->node . '/lxc/' . $lxc->vmid . '/interfaces/')->data;
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
}
