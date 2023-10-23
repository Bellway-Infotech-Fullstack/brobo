<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['name' => 'admin','created_at'=>now(),'updated_at'=>now()],
            ['name' => 'customer','created_at'=>now(),'updated_at'=>now()]
        ];

        DB::table('roles')->insert($data);
    }
}
