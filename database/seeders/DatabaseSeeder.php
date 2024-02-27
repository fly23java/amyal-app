<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AccountAndUserSeeder::class,
            CountrySeeder::class,
            RegionSeeder::class,
            CitySeeder::class,
            UnitSeeder::class,
            PaymentMethodsSeeder::class,
            StatusSeeder::class,
            VehicleTypeSeeder::class,
            GoodsTypesSeeder::class,
        ]);
    }
}
