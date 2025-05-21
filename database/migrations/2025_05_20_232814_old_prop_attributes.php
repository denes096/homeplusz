<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        $categories = [
            'extra' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'extra',
                'description' => 'Extra felszereltség',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'jellemzo' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'jellemzo',
                'description' => 'Jellemzők',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'cimke' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'cimke',
                'description' => 'Címkék',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
        ];

        // 2. Régi adatok beolvasása
        $extrak = DB::connection('old')->table('extrak')->get();

        // 3. Átalakítás és mentés
        foreach ($extrak as $extra) {
            $category = $extra->category;

            // ha ismeretlen kategória, hagyjuk ki
            if (!isset($categories[$category])) continue;

            DB::connection('mariadb')->table('property_attributes')->insert([
                'name' => Str::slug($extra->label ?? 'n/a', '_'),
                'label' => $extra->label ?? '',
                'short_label' => null,
                'property_attribute_category_id' => $categories[$category],
                'type' => 'checkbox',
                'values' => null,
                'prefix' => null,
                'suffix' => null,
                'required' => false,
                'show_in_search' => true,
                'show_in_list' => false,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
