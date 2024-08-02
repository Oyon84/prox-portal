<?php

namespace App\Services;

use App\Models\Task;
use Livewire\Component;

/**
 * Class modalService.
 */
class modalService extends Component
{
    
    public function openModal(array $data, $object = 'task')
    {
        dd($data['modalName']);
        
        $this->dispatch('open-modal', $data['modalName']);
    }
}
