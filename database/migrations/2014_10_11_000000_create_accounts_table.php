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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name_arabic');
            $table->string('name_english')->nullable();
            $table->string('cr_number')->nullable();
            $table->string('bank')->nullable();
            $table->string('iban')->nullable();
            $table->string('account_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_value')->nullable();
            $table->enum('type', [ 'admin' , 'individual_shipper', 'individual_carrier' ,'business_shipper','business_carrier']);
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
        Schema::dropIfExists('accounts');
    }
};
