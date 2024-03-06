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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('loading_city_id');
            $table->string('loading_location')->nullable();
            $table->unsignedBigInteger('unloading_city_id');
            $table->string('unloading_location')->nullable();
            $table->foreignId('vehicle_type_id')->constrained();
            $table->foreignId('goods_id')->constrained();
            $table->unsignedBigInteger('status_id');
           
            $table->decimal('price');
            $table->decimal('carrier_price')->nullable();
            $table->unsignedBigInteger('supervisor_user_id')->nullable();
           

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('loading_city_id')->references('id')->on('cities');
            $table->foreign('unloading_city_id')->references('id')->on('cities');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('supervisor_user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};
