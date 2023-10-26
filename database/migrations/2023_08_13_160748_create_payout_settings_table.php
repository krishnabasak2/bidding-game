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
        Schema::create('payout_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->set('txn_mode', ['1', '2', '3', '4'])->nullable()->comment('1:GPay, 2:PayTM, 3:PhonePe, 4:Bank Account');
            $table->text('payout_number')->nullable();
            $table->text('ac_name')->nullable();
            $table->text('ac_number')->nullable();
            $table->text('ac_ifsc')->nullable();
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
        Schema::dropIfExists('payout_settings');
    }
};
