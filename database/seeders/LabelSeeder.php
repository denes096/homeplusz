<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('labels')->insert(
            [
                'name' => 'CSOK+',
                'color' => '#0000FF',
                'filter' => true,
            ],
        );
        DB::table('labels')->insert(
            [
                'name' => 'PROJEKT',
                'color' => '#FFFF00',
                'filter' => false,
            ],
        );
        DB::table('labels')->insert(
            [
                'name' => 'Kiemelt',
                'color' => '#FF0000',
                'filter' => false,
            ],
        );
    }
}
