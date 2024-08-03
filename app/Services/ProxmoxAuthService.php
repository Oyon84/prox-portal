<?php

namespace App\Services;

use Proxmox\Access;
use proxmox\Cluster;
use Proxmox\Nodes;
use proxmox\Pools;
use Proxmox\ProxmoxException;
use Proxmox\Request;
use proxmox\Storage;

use app\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ProxmoxAuthService
{
    protected $credentials;

    public function __construct($username = null, $password = null, $realm = null)
    {        
        $this->credentials = [
            'hostname' => config('proxmox.server.hostname'),
            'username' => config('proxmox.server.username') ?: $username,
            'password' => config('proxmox.server.password') ?: $password,
            'realm' => config('proxmox.server.realm') ?: $realm,
        ];
        try {
            Request::Login($this->credentials);
        } catch(ProxmoxException) {
            dd('did not work');
        }
        
    }

    public function authenticate($username, $password, $realm = 'pam') : void
    {
        if (isset($this->credentials)) {
            $this->credentials = [
                'hostname' => config('proxmox.server.hostname'),
                'username' => $username,
                'password' => $password,
                'realm'    => $realm,
            ];    
        }
        
        Request::Login($this->credentials);

        Session::put('PVE_Authenticated', true);

    }

    public function request($path)
    {        
        $data = Request::Request($path);

        return $data;
    }

    public function createPveUser($newUserData = array()): void {
        
        // Create Array with information used to send to Proxmox to create new user.
        $data = [
            'userid' => $newUserData['pveUsername'] . '@pve',
            'comment' => Carbon::now()->toDateTimeString() . '- Created in Prox-Portal',
            'email' => $newUserData['email'],
            'enable' => $newUserData['enable'],
            'firstname' => $newUserData['firstName'],
            'lastname' => $newUserData['lastName'],
            'password' => $newUserData['password'],
        ];

        $pveNewUser = new Access;

        $check = $pveNewUser->getUser($data['userid']);

        if (!$check->data) {
            $pveNewUser->createUser($data);

            return;
        }

        throw  ValidationException::withMessages([
            'pveUsername' => 'This PVE Username is already taken.',
        ]);

    }

    public function enableVerifiedUser($newUserData) {

        $userid = $newUserData->pveUsername . '@pve';

        $data = [
            'enable' => 1,
        ];

        $pveNewUser = new Access;

        $pveNewUser->updateUser($userid, $data);
    }

    public function getNodes()
    {
        $proxmox = new Nodes;

        $resultData = $proxmox->listNodes();

        return $resultData->data;
    }

    public function getNodeNetworkDetails($node)  // Get the network details for each node
    {        
        $proxmox = new Nodes;

        $nodeNetworkDetail = $proxmox->Network($node);
            
        foreach ($nodeNetworkDetail->data as $netObject){
            if ($netObject->iface === 'vmbr0') {
                return $netObject->cidr;
            }
        }

        $respons = "Unkown";

        return $respons;
    }

    public function getStatus($node)
    {
        $proxmox = new Request;

        $nodeStatusDetail = $proxmox->Request('/nodes/' . $node . '/status/');
        
        return $nodeStatusDetail->data;
    }

    public function startVM($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;

        $instance = $proxmox->qemuStart($node, $vmid);

        return;

    }

    public function stopVM($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;   

        $instance = $proxmox->qemuStop($node, $vmid, array(['overrule-shutdown' => true]));

        return;

    }

    public function shutdownVM($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;   

        $instance = $proxmox->qemuShutdown($node, $vmid);

        return;

    }

    public function startLXC($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;

        $instance = $proxmox->lxcStart($node, $vmid);

        return;

    }

    public function stopLXC($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;

        $instance = $proxmox->lxcStop($node, $vmid, array(['overrule-shutdown' => true]));

        return;

    }

    public function shutdownLXC($data): void
    {
        $node = $data['node'];
        $vmid = $data['vmid'];

        $proxmox = new Nodes;

        $instance = $proxmox->lxcShutdown($node, $vmid);

        return;

    }
}
