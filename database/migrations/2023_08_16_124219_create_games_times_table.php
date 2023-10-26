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
        Schema::create('games_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->references('id')->on('games_lists')->onDelete('cascade');
            $table->text('title')->nullable();
            $table->text('game_days')->nullable();
            $table->text('start_time')->nullable();
            $table->text('stop_time')->nullable();
            $table->set('status', ['0', '1'])->default('1')->comment('1:active, 0:Inactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games_times');
    }
};
