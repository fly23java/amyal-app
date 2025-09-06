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
        // Add indexes for shipments table
        Schema::table('shipments', function (Blueprint $table) {
            $table->index('account_id');
            $table->index('status_id');
            $table->index('loading_city_id');
            $table->index('unloading_city_id');
            $table->index('vehicle_type_id');
            $table->index('goods_id');
            $table->index('created_at');
            $table->index(['account_id', 'status_id']);
            $table->index(['loading_city_id', 'unloading_city_id']);
        });

        // Add indexes for status_changes table
        Schema::table('status_changes', function (Blueprint $table) {
            $table->index('shipment_id');
            $table->index('status_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['shipment_id', 'status_id']);
        });

        // Add indexes for accounts table
        Schema::table('accounts', function (Blueprint $table) {
            $table->index('name_arabic');
            $table->index('name_english');
            $table->index('created_at');
        });

        // Add indexes for vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('account_id');
            $table->index('vehicle_type_id');
            $table->index(['account_id', 'vehicle_type_id']);
        });

        // Add indexes for cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->index('region_id');
            $table->index('name_arabic');
            $table->index('name_english');
        });

        // Add indexes for contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $table->index('receiver_id');
            $table->index('created_at');
        });

        // Add indexes for contract_details table
        Schema::table('contract_details', function (Blueprint $table) {
            $table->index('contract_id');
            $table->index('loading_city_id');
            $table->index('dispersal_city_id');
            $table->index('vehicle_type_id');
            $table->index('goods_id');
            $table->index(['contract_id', 'loading_city_id', 'dispersal_city_id']);
        });

        // Add indexes for shipment_delivery_details table
        Schema::table('shipment_delivery_details', function (Blueprint $table) {
            $table->index('shipment_id');
            $table->index('vehicle_id');
            $table->index('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove indexes from shipments table
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex(['account_id']);
            $table->dropIndex(['status_id']);
            $table->dropIndex(['loading_city_id']);
            $table->dropIndex(['unloading_city_id']);
            $table->dropIndex(['vehicle_type_id']);
            $table->dropIndex(['goods_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['account_id', 'status_id']);
            $table->dropIndex(['loading_city_id', 'unloading_city_id']);
        });

        // Remove indexes from status_changes table
        Schema::table('status_changes', function (Blueprint $table) {
            $table->dropIndex(['shipment_id']);
            $table->dropIndex(['status_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['shipment_id', 'status_id']);
        });

        // Remove indexes from accounts table
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex(['name_arabic']);
            $table->dropIndex(['name_english']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['account_id']);
            $table->dropIndex(['vehicle_type_id']);
            $table->dropIndex(['account_id', 'vehicle_type_id']);
        });

        // Remove indexes from cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex(['region_id']);
            $table->dropIndex(['name_arabic']);
            $table->dropIndex(['name_english']);
        });

        // Remove indexes from contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropIndex(['receiver_id']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes from contract_details table
        Schema::table('contract_details', function (Blueprint $table) {
            $table->dropIndex(['contract_id']);
            $table->dropIndex(['loading_city_id']);
            $table->dropIndex(['dispersal_city_id']);
            $table->dropIndex(['vehicle_type_id']);
            $table->dropIndex(['goods_id']);
            $table->dropIndex(['contract_id', 'loading_city_id', 'dispersal_city_id']);
        });

        // Remove indexes from shipment_delivery_details table
        Schema::table('shipment_delivery_details', function (Blueprint $table) {
            $table->dropIndex(['shipment_id']);
            $table->dropIndex(['vehicle_id']);
            $table->dropIndex(['delivery_status']);
        });
    }
};