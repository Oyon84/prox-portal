<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\ProxmoxAuthService;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    public function getAllTasks(ProxmoxAuthService $proxmox)
    {
        return $proxmox->Request('/cluster/tasks')->data;
    }

    public function storeTasks(array $tasks)
    {
        foreach($tasks as $task) {
            $taskDetails = explode(":", $task->upid);

            $node = Node::where('name', $taskDetails[1])->first();

            $userObject = explode("@", $taskDetails[7]);

            $user = User::where('pveUsername', $userObject[0])->first();

            $taskRecord = [
                'uid' => $taskDetails[2] . ":" . $taskDetails[3] . ':' . $taskDetails[4],
                'vmid' => (int)$taskDetails[6],
                'type' => $taskDetails[5],
                'saved' => (bool)$task->saved,
                'node_id' => $node->id,
                'user_id' => $user->id,
                'starttime' => $task->starttime,
                'stoptime' => $task->endtime,
                'status' => $task->status,
            ];

            $taskCheck = Task::where('uid', $taskRecord['uid'])->first();

            if ($taskCheck === null) {
                $taskCheck = Task::create($taskRecord);
            }
        }
    }

    public function getTaskByUid($uid)
    {
        return Task::where('uid', $uid)->first();
    }
}
