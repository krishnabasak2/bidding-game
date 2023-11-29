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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('txn_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->set('type', ['0', '1'])->nullable()->comment('0:debit, 1:credit');
            $table->set('status', ['0', '1', '2', '3', '4', '5'])->nullable()->comment('0:Bidding, 1:Winning, 2:Deposit, 3:Payout, 4:Admin Debit, 5:Admin Credit');
            $table->decimal('current_balance', 10, 2)->default(0);
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
        Schema::dropIfExists('transactions');
    }
};
