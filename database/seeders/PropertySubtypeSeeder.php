<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PropertySubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path=database_path('sql/property_subtypes.sql');
        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            throw new \Exception("SQL file not found: $path");
        }
    }
}
