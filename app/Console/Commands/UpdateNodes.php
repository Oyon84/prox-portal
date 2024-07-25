<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Node;
use App\Services\ProxmoxAuthService;


class UpdateNodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nodes:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates nodes on the database if new nodes are added';


    private function consoleHeader()
    {
        $this->newLine();
        $this->comment('Prox Portal Node discovery');
        $this->line('##########################');
        $this->newLine();
        $this->line('This command checks for all nodes in your proxmox cluster in the database and adds any new node to the database.');
        $this->confirm('Do you want to continue?');
    }

    private function consoleAddNewNode($node)
    {
        $this->newLine();
        $this->comment('Node: ' . $node . '- New node found');
        $this->confirm('Do you want to add ' . $node . ' to the prox portal database?');
        $this->newLine();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Show header on the console, stating some information about the commando and wait for a confirmation
        $this->consoleHeader();

        $proxmox = new ProxmoxAuthService();

        $nodes = $proxmox->getNodes();

        foreach ($nodes as $node) {

            $this->line('Node: ' . $node->node . '- checking database');

            $storedNode = Node::where('name', '=', $node->node)->first();

            if ($storedNode === null) {

                // Show information when new node is found and ask for confirmation to add the node to the database
                $this->consoleAddNewNode($node->node);

                $this->info('New Node: ' . $node->node . '- Adding to database');

                // Store node in the Database
                $createNode = Node::create([
                    'name' => $node->node,
                    'ip_address' => $proxmox->getNodeNetworkDetails($node->node),
                    'status' => 'online',
                    'cpu_model' => $proxmox->getStatus($node->node)->cpuinfo->model,
                    'cpu' => $proxmox->getStatus($node->node)->cpuinfo->cpus,
                    'memory' => $proxmox->getStatus($node->node)->memory->total,
                ]);

                dd($createNode);
            }

            $this->line('Node: ' . $node->node . '- Allready in database');
        }
    }
}
