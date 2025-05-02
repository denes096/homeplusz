<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('information_categories')->insert([
            'name' => 'IKategoria 1',
        ]);

        DB::table('information_categories')->insert([
            'name' => 'IKategoria 2',
        ]);

        DB::table('information_categories')->insert([
            'name' => 'IKategoria 3',
        ]);

        DB::table('information')->insert([
            'name' => 'Info 1',
            'information_category_id' => 1,
            'description' => 'Informacio 1'
        ]);
        DB::table('information')->insert([
            'name' => 'Info 2',
            'information_category_id' => 2,
            'description' => 'Informacio 2'
        ]);
        DB::table('information')->insert([
            'name' => 'Info 3',
            'information_category_id' => 3,
            'description' => 'Informacio 3'
        ]);
    }
}
