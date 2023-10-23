<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Master Admin',
            'mobile_number' => '01759412381',
            'email' => 'admin_brobo@mailinator.com',
            'image' => 'def.png',
            'password' => bcrypt(12345678),
            'remember_token' => Str::random(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
    }
}
