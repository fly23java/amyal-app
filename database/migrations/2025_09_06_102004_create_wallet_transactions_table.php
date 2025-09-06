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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 30)->unique();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_wallet_id')->nullable()->constrained('wallets')->onDelete('set null');
            $table->string('type'); // credit, debit, transfer, refund, fee
            $table->string('category'); // deposit, withdrawal, payment, refund, transfer, fee, cashback
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0.00);
            $table->decimal('net_amount', 15, 2); // amount - fee
            $table->string('currency', 3)->default('SAR');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->string('payment_method')->nullable(); // card, bank, wallet, cash
            $table->string('reference_id')->nullable(); // External reference
            $table->string('gateway_transaction_id')->nullable(); // Payment gateway ID
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional transaction data
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['wallet_id', 'type']);
            $table->index(['wallet_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['reference_id']);
            $table->index(['gateway_transaction_id']);
            $table->index(['created_at']);
            $table->index(['type', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};