<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name_english' => 'Ton', 'name_arabic' => 'ﻃﻦ'],
            ['name_english' => 'Pallet', 'name_arabic' => 'بليت'],
            ['name_english' => 'Box', 'name_arabic' => 'ﻛﺮﺗﻮﻥ'],
            ['name_english' => 'KG', 'name_arabic' => 'كيلو جرام'],
            ['name_english' => 'Case', 'name_arabic' => 'ﺣﺰمة'],
            ['name_english' => 'Container', 'name_arabic' => 'حاوية'],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert($unit);
        }
    }
}
