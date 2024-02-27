<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            ['name_english' => 'Cash', 'name_arabic' => 'كاش'],
            ['name_english' => 'ATM Card', 'name_arabic' => 'بطاقة صراف'],
            ['name_english' => 'Credit Card', 'name_arabic' => 'بطاقة ﺇﺋﺘمانية'],
            ['name_english' => 'SDAD', 'name_arabic' => 'ﺳﺪﺍﺩ'],
            ['name_english' => 'Check', 'name_arabic' => 'شيك'],
            ['name_english' => 'Bank Transfer', 'name_arabic' => 'تحويل بنكي'],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            DB::table('payment_methods')->insert($paymentMethod);
        }
    }
}
