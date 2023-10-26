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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->text('user_id')->nullable();
            $table->text('name')->nullable();
            $table->text('phone')->unique()->nullable();
            $table->text('email')->nullable();
            $table->string('password')->nullable();
            $table->set('role', ['0', '1'])->default('1')->comment('0:Admin,1:User');
            $table->set('status', ['0', '1'])->default('1')->comment('0:Suspend,1:Active');
            $table->bigInteger('referer_uid')->nullable()->comment('Referer user id');
            $table->decimal('wallet', 10, 2)->default(0);
            $table->text('otp')->nullable();
            $table->longText('game_settings')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
