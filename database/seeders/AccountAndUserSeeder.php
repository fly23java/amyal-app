<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AccountAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create([
            'name_arabic' => 'شركة اميال اللوجستسة',
            'type' => 'admin',
            
        ]);
    
        // Create admin user for the account
        $account->users()->create([
            'name' => 'شركة اميال',
            'email' => 'admin@admin.com',
            'account_id' => $account->id,
            'password' =>  Hash::make('12345678'),
            'type' =>  'admin',
            'status' => 'active',
           
        ]);
        
    }
}
