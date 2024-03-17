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
        Schema::create('price_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_id');
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->unsignedBigInteger('goods_id')->nullable();
            $table->unsignedBigInteger('loading_city_id');
            $table->unsignedBigInteger('dispersal_city_id');
            $table->decimal('price', 9, 2);
            $table->unsignedBigInteger('accepted_user_id')->nullable();
            $table->timestamps();

            $table->foreign('price_id')->references('id')->on('prices');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('loading_city_id')->references('id')->on('cities');
            $table->foreign('dispersal_city_id')->references('id')->on('cities');
            $table->foreign('accepted_user_id')->references('id')->on('users');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_details');
    }
};
