<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            ['name_english' => 'Riyadh', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟرياﺽ', 'country_id' => 1],
            ['name_english' => 'Makkah', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﻣﻜﺔ ﺍﻟﻤﻜﺮﻣﺔ', 'country_id' => 1],
            ['name_english' => 'Madinah', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟﻤﺪﻳﻨﺔ ﺍﻟﻤﻨﻮﺭﺓ', 'country_id' => 1],
            ['name_english' => 'Al-Qasim', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟﻘصيم', 'country_id' => 1],
            ['name_english' => 'Eastern Province', 'name_arabic' => 'ﺍﻟﻤﻨﻄﻘﺔ ﺍﻟ ﻗ ﺔ', 'country_id' => 1],
            ['name_english' => 'Aseer', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﻋﺴير', 'country_id' => 1],
            ['name_english' => 'Hael', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺣﺎﺋﻞ', 'country_id' => 1],
            ['name_english' => 'Tabouk', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺗﺒﻮﻙ', 'country_id' => 1],
            ['name_english' => 'Al-Baaha', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟباﺣﺔ', 'country_id' => 1],
            ['name_english' => 'Northern Borders', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟﺤﺪﻭﺩ ﺍﻟﺸﻤﺎلية', 'country_id' => 1],
            ['name_english' => 'Al-Jowf', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺍﻟﺠﻮﻑ', 'country_id' => 1],
            ['name_english' => 'Jazan', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﺟﺎﺯﺍﻥ', 'country_id' => 1],
            ['name_english' => 'Najran', 'name_arabic' => 'ﻣﻨﻄﻘﺔ ﻧﺠﺮﺍﻥ', 'country_id' => 1],
        ];

        DB::table('regions')->insert($regions);
    }
}
