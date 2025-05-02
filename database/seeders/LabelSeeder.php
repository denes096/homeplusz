<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ],
        );
        DB::table('labels')->insert(
            [
                'name' => 'PROJEKT',
                'color' => '#FFFF00',
            ],
        );
        DB::table('labels')->insert(
            [
                'name' => 'Kiemelt',
                'color' => '#FF0000',
            ],
        );
    }
}
