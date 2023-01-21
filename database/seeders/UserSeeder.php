<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'id' => 1,
             'name' => 'Administrator',
             'email' => 'admin@admin.com',
             'role' => 1 ,
             'email_verified_at' => now(), 
            'password'=> Hash::make('password')
        ];    

        User::updateOrCreate(['id' => 1],$items);
    }
}
