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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('txn_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('txn_number')->nullable();
            $table->set('txn_method', ['1', '2', '3', '4'])->nullable()->comment('1:GPay, 2:PayTM, 3:PhonePe, 4:Others');
            $table->set('status', ['0', '1', '2'])->default('2')->comment('0:Failed, 1:Success, 2:Pending');
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('deposits');
    }
};
