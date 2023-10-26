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
        Schema::create('games_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->references('id')->on('games_lists')->onDelete('cascade');
            $table->foreignId('time_id')->references('id')->on('games_times')->onDelete('cascade');
            $table->text('patti_win_value')->nullable();
            $table->text('jodi_win_value')->nullable();
            $table->text('single_win_value')->nullable();
            $table->text('cp_win_value')->nullable();
            $table->set('status', ['0', '1'])->default('0')->comment('1:decide, 0:undecide');
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
        Schema::dropIfExists('games_results');
    }
};
