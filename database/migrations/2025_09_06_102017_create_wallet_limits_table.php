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
        Schema::create('wallet_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->string('type'); // daily, weekly, monthly, yearly, transaction
            $table->string('operation'); // deposit, withdrawal, transfer, total
            $table->decimal('limit_amount', 15, 2);
            $table->decimal('used_amount', 15, 2)->default(0.00);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('period_start');
            $table->date('period_end');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['wallet_id', 'type', 'operation']);
            $table->index(['period_start', 'period_end']);
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
        Schema::dropIfExists('wallet_limits');
    }
};