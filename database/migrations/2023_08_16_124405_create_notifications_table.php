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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->set('to', ['0', '1', '2'])->comment('0:Admin, 1:User, 2:Both');
            $table->set('from', ['0', '1', '2'])->comment('0:Admin, 1:System, 2:Master');
            $table->bigInteger('user_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('message')->nullable();
            $table->set('status', ['0', '1'])->comment('0:Unseen, 1:seen');
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
        Schema::dropIfExists('notifications');
    }
};
