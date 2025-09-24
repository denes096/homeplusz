<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('property_attributes')->insert(
            [
                'name' => 'condition',
                'label' => 'Állapot belül',
                'property_attribute_category_id' => 1,
                'type' => 'select',
                'values' => '[{"id":61,"label":"Új"},{"id":62,"label":"Felújított"},{"id":63,"label":"Jó"},{"id":64,"label":"Lakható"},{"id":65,"label":"Felújítandó"},{"id":66,"label":"Bontandó"}]',
                'required' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'building_condition',
                'label' => 'Allapot kívülről',
                'property_attribute_category_id' => 1,
                'type' => 'select',
                'values' => '[{"id":61,"label":"Új"},{"id":62,"label":"Felújított"},{"id":63,"label":"Jó"},{"id":64,"label":"Lakható"},{"id":65,"label":"Felújítandó"},{"id":66,"label":"Bontandó"}]',
                'required' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'built',
                'label' => 'Építés éve',
                'short_label' => 'építés',
                'property_attribute_category_id' => 1,
                'type' => 'number',
                'required' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'last_renovation',
                'label' => 'Felújítás éve',
                'property_attribute_category_id' => 1,
                'type' => 'number',
                'required' => false,
            ],
        );

        DB::table('property_attributes')->insert(
            [
                'name' => 'property_area',
                'label' => 'Alapterület',
                'suffix' => 'm2',
                'property_attribute_category_id' => 1,
                'type' => 'number',
                'required' => true,
                'show_in_list' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'number_of_rooms',
                'label' => 'Szobák száma',
                'short_label' => 'szoba',
                'property_attribute_category_id' => 1,
                'type' => 'number',
                'required' => true,
                'show_in_list' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'number_of_bathrooms',
                'label' => 'Fürdőszobák száma',
                'short_label' => 'fürdőszoba',
                'property_attribute_category_id' => 1,
                'type' => 'number',
                'required' => true,
                'show_in_list' => true,
            ],
        );

        DB::table('property_attributes')->insert(
            [
                'name' => 'area',
                'label' => 'Telek alapterülete',
                'short_label' => 'telek',
                'suffix' => 'm2',
                'property_attribute_category_id' => 2,
                'type' => 'number',
                'required' => true,
            ],
        );

        DB::table('property_attributes')->insert(
            [
                'name' => 'slopes',
                'label' => 'Telek domborzata',
                'property_attribute_category_id' => 2,
                'type' => 'select',
                'values' => json_encode(['Sík', 'Enyhe lejtős', 'Lejtős', 'Teraszos'], JSON_UNESCAPED_UNICODE),
                'required' => true,
            ],
        );
        DB::table('property_attributes')->insert(
            [
                'name' => 'parking',
                'label' => 'Parkolási lehetőségek',
                'property_attribute_category_id' => 2,
                'type' => 'select',
                'values' => json_encode(['Garázs', 'Gk. beálló', 'Utcán/közterületen', 'Teremgarázs'], JSON_UNESCAPED_UNICODE),
                'required' => true,
            ],
        );
    }
}
