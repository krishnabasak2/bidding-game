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
        Schema::create('games_lists', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->decimal('patti_win_value', 10, 2)->nullable();
            $table->decimal('jodi_win_value', 10, 2)->nullable();
            $table->decimal('single_win_value', 10, 2)->nullable();
            $table->decimal('cp_win_value', 10, 2)->nullable();
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
        Schema::dropIfExists('games_lists');
    }
};
