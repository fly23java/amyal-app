<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleTypes = [
            ['name_arabic' => 'تريله سطحة', 'name_english' => 'Flatbed Trailer'],
            ['name_arabic' => 'التريلا ستارة', 'name_english' => 'Curtain Trailer'],
            ['name_arabic' => 'تريله ثلاجة', 'name_english' => 'Refrigerated Trailer'],
            ['name_arabic' => 'تريله جوانب عالية', 'name_english' => 'High-Sided Trailer'],
            ['name_arabic' => 'تريله جوانب ألماني', 'name_english' => 'German-Sided Trailer'],
            ['name_arabic' => 'تريلا', 'name_english' => 'Trailer'],
            ['name_arabic' => 'سقس', 'name_english' => 'Saks'],
            ['name_arabic' => 'لوري', 'name_english' => 'Lorry'],
            ['name_arabic' => 'دينا', 'name_english' => 'Dyna'],
            ['name_arabic' => 'بك اب "ونيت"', 'name_english' => 'Pickup Truck (One-Ton)'],
        ];

        foreach ($vehicleTypes as $type) {
            DB::table('vehicle_types')->insert($type);
        }
    }
}
