@props([
    'vmData',
    'lxcData',
    ])

{{-- @dump($vmData, $lxcData) --}}

<div class="h-5/6 overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center gap-2 pb-3">
            <div>
                <x-svg.explorer></x-svg.explorer>   
            </div>
            <div class="flex gap-2 items-center w-full">
                <h1 class="font-bold text-xl mr-10 text-nowrap">Resource Explorer</h1>
                <x-text-input 
                    class="block mt-1 w-full" 
                    placeholder="Lookup resources"/>
            </div>
        </div>
        <hr class="dark:border-gray-700 mb-3">
        <div x-data="{ showVM: false }" x-init="$watch('showVM', value => console.log(value))" class="overflow-y-auto ">
            <table class="table-fixed min-w-full divide-y divide-gray-200 dark:divide-gray-600 h-5/6 overflow-y-auto overflow-x-auto">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <x-resource-table.resource-header-row
                        cell1="Type"
                        cell2="Resource"
                        cell3="Details"
                        cell4="Status"
                        cell5="IP"
                        cell6="Actions" />
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <div x-show="showVM">
                        <x-resource-table.resource-type-row>
                            <x-slot name="svg">
                                <x-svg.vm></x-svg.vm>
                            </x-slot-svg>
                            Virtual Machines
                        </x-resource-table.resource-type-row>
                        {{-- Virtual Machines loop --}}
                        <div>
                            @foreach ($vmData as $key => $vm)
                                <x-resource-table.resource-item-row
                                    name="{{ ucfirst($vm->name) }}"
                                    nameSub="{{ isset($vm->template) ? 'Template' : 'VMID: ' . $vm->vmid }}"
                                    details="CPU: {{ $vm->cpus }} | MEM: {{ round($vm->mem / 1073741824, 2) }} GB | NIC: 1"
                                    detailsSub="{{ $vm->status == 'stopped' ? 'N/A' : $this->setUptime($vm->uptime) }}"
                                    status="{{ ucfirst($vm->status) }}"
                                    ip="172.16.5.4"
                                    favorite="{{ (bool)rand(0,1) }}">    
                                    <x-slot name="svg">
                                        <x-svg.vm size="size-5"></x-svg.vm>
                                    </x-slot>
                                </x-resource-table.resource-item-row>                      
                            @endforeach   
                        </div>
                    </div>
                    
                    {{-- Row --}}
                    <x-resource-table.resource-type-row>
                        <x-slot name="svg">
                            <x-svg.container></x-svg.container>
                        </x-slot-svg>
                        Containers
                    </x-resource-table.resource-type-row>
                    {{-- Container Loop --}}
                    @foreach ($lxcData as $key => $lxc)
                        <x-resource-table.resource-item-row
                            name="{{ ucfirst($lxc->name) }}"
                            nameSub="{{ isset($lxc->template) ? 'Template' : 'VMID: ' . $lxc->vmid }}"
                            details="CPU: {{ $lxc->cpus }} | MEM: {{ round($lxc->mem / 1024 / 1024 / 1024, 2) }} GB | NIC: 1"
                            detailsSub="{{ $lxc->status == 'stopped' ? 'N/A' : $this->setUptime($lxc->uptime) }}"
                            status="{{ ucfirst($lxc->status) }}"
                            ip="{{ $lxc->ip }}"
                            favorite="{{ (bool)rand(0,1) }}">    
                            <x-slot name="svg">
                                <x-svg.container size="size-5"></x-svg.container>
                            </x-slot>
                        </x-resource-table.resource-item-row>                      
                    @endforeach
                    @foreach ($lxcData as $key => $lxc)
                        <x-resource-table.resource-item-row
                            name="{{ ucfirst($lxc->name) }}"
                            nameSub="{{ isset($lxc->template) ? 'Template' : 'VMID: ' . $lxc->vmid }}"
                            details="CPU: {{ $lxc->cpus }} | MEM: {{ round($lxc->mem / 1024 / 1024 / 1024, 2) }} GB | NIC: 1"
                            detailsSub="{{ $lxc->status == 'stopped' ? 'N/A' : $this->setUptime($lxc->uptime) }}"
                            status="{{ ucfirst($lxc->status) }}"
                            ip="{{ $lxc->ip }}"
                            favorite="{{ (bool)rand(0,1) }}">    
                            <x-slot name="svg">
                                <x-svg.container size="size-5"></x-svg.container>
                            </x-slot>
                        </x-resource-table.resource-item-row>                      
                    @endforeach
                    {{-- Row --}}
                    <x-resource-table.resource-type-row>
                        <x-slot name="svg">
                            <x-svg.net></x-svg.net>
                        </x-slot-svg>
                        Networks
                    </x-resource-table.resource-type-row>
                    {{-- Row --}}
                    <x-resource-table.resource-item-row
                        name="Fhs7 Server Farm"
                        nameSub="Vlan 300"
                        details="IP: 254  | Used: 5 | Left: 249"
                        detailsSub="Subnet: /24"
                        status="Active"
                        ip="172.16.2.0/24"
                        favorite="{{ (bool)rand(0,1) }}">    
                        <x-slot name="svg">
                            <x-svg.net size="size-5"></x-svg.net>
                        </x-slot>
                    </x-resource-table.resource-item-row>                  
                    <!-- More rows... -->
            
                </tbody>
            </table>
        </div>
    </div>
</div>