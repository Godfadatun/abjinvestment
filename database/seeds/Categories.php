<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                'name' => 'Oil & Gas',
                'description' => 'Greatest investment of life',
            ]
        );


        DB::table('categories')->insert(

            [
                'name' => 'Real Estate',
                'description' => 'Greatest investment of life',
            ]
            );
    }
}
