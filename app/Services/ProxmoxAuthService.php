<?php

namespace App\Services;

use Proxmox\Access;
use proxmox\Cluster;
use proxmox\Nodes;
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

    public function __construct($username, $password, $realm)
    {        
        $this->credentials = [
            'hostname' => config('proxmox.server.hostname'),
            'username' => config('proxmox.server.username') ?: $username,
            'password' => config('proxmox.server.password') ?: $password,
            'realm' => config('proxmox.server.realm') ?: $realm,
        ];

        Request::Login($this->credentials);
    }

    public function authenticate($username, $password, $realm = 'pam')
    {
        if (isset($this->credentials)) {
            $this->credentials = [
                'hostname' => '172.16.1.204',
                'username' => $username,
                'password' => $password,
                'realm'    => $realm,
            ];    
        }
        
        Request::Login($this->credentials);

        $pve_access = new Access;

        $pve_user = $pve_access->getUser($username . '@' . $realm);

        return $pve_user;
    }

    public function request($path)
    {        
        $data = Request::Request($path);

        return $data;
    }

    public function getProxmox()
    {
        // Retrieve ticket from session and reuse the Proxmox client
        if (Session::has('proxmox_ticket')) {
            $this->proxmox->setTicket(Session::get('proxmox_ticket'));
        } else {
            $this->authenticate();
        }

        return $this->proxmox;
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
}
