<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_number', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('wallet_type')->default('main'); // main, savings, business
            $table->string('currency', 3)->default('SAR');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('pending_balance', 15, 2)->default(0.00);
            $table->decimal('reserved_balance', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('status')->default('active'); // active, suspended, closed
            $table->string('pin_hash')->nullable();
            $table->timestamp('pin_set_at')->nullable();
            $table->json('settings')->nullable(); // JSON for wallet settings
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'wallet_type']);
            $table->index(['currency', 'status']);
            $table->index('wallet_number');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};