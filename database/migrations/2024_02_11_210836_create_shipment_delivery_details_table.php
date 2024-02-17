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
        Schema::create('shipment_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->timestamp('shipment_departure_date')->nullable();
            $table->timestamp('shipment_arrival_date')->nullable();
            $table->timestamp('loading_time')->nullable();
            $table->timestamp('unloading_time')->nullable();
            $table->timestamp('arrival_time')->nullable();
            $table->timestamp('departure_time')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('delivery_document')->nullable();
            $table->string('delay_fine')->nullable();
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
        Schema::dropIfExists('shipment_delivery_details');
    }
};
