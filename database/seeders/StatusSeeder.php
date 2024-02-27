<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name_english' => 'Active',
                'name_arabic' => 'نشط',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
            [
                'name_english' => 'Canceled',
                'name_arabic' => 'ملغية',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
            [
                'name_english' => 'Closed',
                'name_arabic' => 'ﻣﻐﻠﻘﺔ',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
            [
                'name_english' => 'Finished',
                'name_arabic' => 'منتهية',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
            [
                'name_english' => 'Not Delivered',
                'name_arabic' => 'ﻟﻢ تسلم',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
            [
                'name_english' => 'Multi Status',
                'name_arabic' => 'ﻣﺘﻌﺪﺩ ﺍﻟﺤﺎﻻﺕ',
                'message_text_in_english' => 'test',
                'message_text_in_arabic' => 'test',
                'confirm_sending_the_message' => true,
            ],
        ];

        // Insert data into the 'statuses' table
        DB::table('statuses')->insert($statuses);
    }
}
