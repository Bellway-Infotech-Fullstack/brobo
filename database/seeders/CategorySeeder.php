<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
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
                    ['name' => 'Tent','parent_id' => 0,'created_at'=>now(),'updated_at'=>now()],
                    ['name' => 'Flower','parent_id' => 0,'created_at'=>now(),'updated_at'=>now()]
                ];

        DB::table('categories')->insert($data);
    }
}
