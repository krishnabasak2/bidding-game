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
        Schema::create('bids_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('game_id')->references('id')->on('games_lists')->onDelete('cascade');
            $table->foreignId('time_id')->references('id')->on('games_times')->onDelete('cascade');
            $table->foreignId('result_id')->references('id')->on('games_results')->onDelete('cascade');
            $table->set('game_type', ['0', '1', '2', '3'])->nullable()->comment('0:CP, 1:Single, 2:Jodi, 3:Patti');
            $table->text('bid_number')->nullable();
            $table->decimal('bid_amount', 10, 2)->default(0);
            $table->decimal('won_amount', 10, 2)->nullable();
            $table->set('status', ['0', '1', '2'])->comment('status (2:Pending, 1:Win, 0:Loose');
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
        Schema::dropIfExists('bids_histories');
    }
};
