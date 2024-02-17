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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name');
            $table->string('sequence_number');
            $table->string('plate');
            $table->string('right_letter');
            $table->string('middle_letter');
            $table->string('left_letter');
            $table->Integer('plate_type');
           
            
            $table->foreignId('vehicle_type_id')->constrained();
            $table->foreignId('account_id')->constrained();
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
        Schema::dropIfExists('vehicles');
    }
};
