<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Package extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert(
            [
                'category_id' => 1,
                'description' => 'plenty money',
                'name' => 'silver',
                'amount' => 200000,
                'fee' => 10,
                'roi' => 20,
                'duration' => 6,
            ]
        );

        DB::table('packages')->insert(

            [
                'category_id' =>  2,
                'description' => 'plenty money',
                'name' => 'gold',
                'amount' => 200000,
                'fee' => 10,
                'roi' => 20,
                'duration' => 6,
            ]
        );
    }
}
