<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_categories')->insert([
            'name' => 'Kategoria 1',
        ]);

        DB::table('service_categories')->insert([
            'name' => 'Kategoria 2',
        ]);

        DB::table('service_categories')->insert([
            'name' => 'Kategoria 3',
        ]);

        DB::table('services')->insert([
            'name' => 'Service 1',
            'featured' => true,
            'service_category_id' => 1,
            'description' => '<bold>Ez egy félkövér leírás.</bold> Service 1',
        ]);
        DB::table('services')->insert([
            'name' => 'Service 2',
            'featured' => true,
            'service_category_id' => 1,
            'description' => '<bold>Ez egy félkövér leírás.</bold> Service 2',
        ]);
        DB::table('services')->insert([
            'name' => 'Service 3',
            'service_category_id' => 1,
            'description' => '<bold>Ez egy félkövér leírás.</bold> Service 3',
        ]);
        DB::table('services')->insert([
            'name' => 'Service 4',
            'service_category_id' => 2,
            'description' => '<bold>Ez egy félkövér leírás.</bold> Service 4',
        ]);
        DB::table('services')->insert([
            'name' => 'Service 5',
            'featured' => true,
            'service_category_id' => 2,
            'description' => '<bold>Ez egy félkövér leírás.</bold> Service 5',
        ]);
    }
}
