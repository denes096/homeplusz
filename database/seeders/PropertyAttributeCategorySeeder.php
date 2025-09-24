<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyAttributeCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('property_attribute_categories')->insert(
            [
                'name' => 'Épület / Lakás',
            ],
        );

        DB::table('property_attribute_categories')->insert(
            [
                'name' => 'Telek és környezet',
            ],
        );

        DB::table('property_attribute_categories')->insert(
            [
                'name' => 'Közművek',
            ],
        );

        DB::table('property_attribute_categories')->insert(
            [
                'name' => 'Extrák',
            ],
        );

    }
}
