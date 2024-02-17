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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
           
            $table->string('name_arabic')->nullable();
            $table->string('name_english')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();;
            $table->string('phone')->nullable();
            $table->string('identity_number')->nullable();
            $table->dateTime('date_of_birth_hijri')->nullable();
            $table->dateTime('date_of_birth_gregorian')->nullable();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
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
        Schema::dropIfExists('drivers');
    }
};
