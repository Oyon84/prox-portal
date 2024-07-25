<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();                                           // Laravel ID for relationships with other objects
            $table->string('name');                                 // Name of the node
            $table->string('ip_address');                           // IP address to access the node / IP that is reacheble
            $table->string('status')->nullable();                   // Status of the node, online, offline, maintanance mode
            $table->boolean('maintanance_mode')->default(1);        // Node with maintanance mode enabled 
            $table->boolean('portal_access_node')->default(0);      // Nodes with true are available for prox portal to connect to.
            $table->boolean('available')->default(0);               // Is the node available for resource placement
            $table->string('cpu_model');                            // Model of the CPU
            $table->unsignedTinyInteger('cpu');                     // Number of CPU threats
            $table->unsignedBigInteger('memory');                   // Amount of memory
            $table->timestamps();                                   // Timestamps of the record
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
