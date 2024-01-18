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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->references('id')->on('users');
            $table->text('app_name')->nullable();
            $table->text('url')->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->nullable();
            $table->text('currency_word')->nullable();
            $table->text('currency_symbol')->nullable();
            $table->text('currency_icon')->nullable();
            $table->text('currency_value')->default('1');
            $table->decimal('new_ac_bonus')->default(0);
            $table->decimal('referrer_bonus')->default(0);
            $table->decimal('joiner_bonus')->default(0);
            $table->longText('logo')->nullable();
            $table->longText('baner')->nullable();
            $table->longText('banner_links')->nullable();
            $table->longText('ads')->nullable();
            $table->longText('ads_text')->nullable();
            $table->longText('ads_link')->nullable();
            $table->set('ads_status', ['0', '1'])->default('1')->comment('0:Off, 1:On');
            $table->longText('game_rule')->nullable();
            $table->longText('add_money_details')->nullable();
            $table->longText('withdrawal_details')->nullable();
            $table->text('notice')->nullable();
            $table->text('message')->nullable();
            $table->set('withdrawal', ['0', '1', '2'])->default('1')->comment('0:Off, 1:On, 2:Custom');
            $table->text('wd_start_time')->nullable();
            $table->text('wd_end_time')->nullable();
            $table->text('wd_days')->nullable();
            $table->decimal('min_withdraw', 10, 2)->nullable();
            $table->decimal('min_add_money', 10, 2)->nullable();
            $table->integer('max_single_bet')->nullable();
            $table->decimal('max_bet_amount', 10, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
