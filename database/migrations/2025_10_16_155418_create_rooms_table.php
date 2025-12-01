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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedInteger('max_players')->default(8);
            $table->unsignedInteger('rounds')->default(8);
            $table->unsignedInteger('round_time')->default(80);
            $table->string('status')->default('waiting');
            $table->unsignedInteger('current_round')->default(1);
            $table->foreignId('current_drawer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('current_word')->nullable();
            $table->timestamp('round_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
