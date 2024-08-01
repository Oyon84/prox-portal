<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Node;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->unsignedInteger('vmid');
            $table->string('type');
            $table->boolean('saved');
            $table->foreignIdFor(model: Node::class, column: 'node_id');
            $table->foreignIdFor(model: User::class, column: 'user_id');
            $table->integer('starttime');
            $table->integer('stoptime');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
