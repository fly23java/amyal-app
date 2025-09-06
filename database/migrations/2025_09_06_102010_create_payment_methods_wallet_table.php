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
        Schema::create('wallet_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->string('type'); // card, bank_account, mobile_payment
            $table->string('provider'); // visa, mastercard, mada, stc_pay, apple_pay
            $table->string('last_four', 4)->nullable(); // Last 4 digits
            $table->string('token')->nullable(); // Tokenized payment method
            $table->string('fingerprint')->nullable(); // Unique identifier
            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->json('metadata')->nullable(); // Additional payment method data
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['wallet_id', 'type']);
            $table->index(['wallet_id', 'is_default']);
            $table->index('token');
            $table->index('fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_payment_methods');
    }
};