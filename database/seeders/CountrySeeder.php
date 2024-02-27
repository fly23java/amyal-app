<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name_english' => 'Saudi Arabia',
            'name_arabic' => 'المملكة العربية السعودية',
            'alpha2_code' => 'SA',
            'alpha3_code' => 'SAU',
            'phone_code' => '966',
        ]);
    }
}
