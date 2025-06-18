<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SettlementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
//    public function run(): void
//    {
//        $path=database_path('sql/settlements.sql');
//        if (File::exists($path)) {
//            DB::unprepared(File::get($path));
//        } else {
//            throw new \Exception("SQL file not found: $path");
//        }
//    }

    public function run()
    {
        DB::disableQueryLog();

        // Kikapcsoljuk a foreign key check-et és auto incrementet
        Schema::disableForeignKeyConstraints();
        DB::table('settlement_parts')->truncate();
        DB::table('settlements')->truncate();
        Schema::enableForeignKeyConstraints();

        $torzsadat = DB::connection('old')->table('torzsadat')
            ->where('datatype', 'varos')
            ->orderBy('parent')
            ->get();

        $settlements = [];
        $parts = [];

        foreach ($torzsadat as $item) {
            if ($item->parent == 0) {
                $settlements[$item->Id] = [
                    'id' => $item->Id,
                    'postal_code' => $this->guessPostalCode($item->label),
                    'name' => $item->label,
                    'part' => null,
                    'county' => 'Pest', // vagy más logika alapján
                    'area' => 'Közép-Magyarország', // vagy más logika alapján
                ];
            } else {
                $parts[] = [
                    'id' => $item->Id,
                    'settlement_id' => $item->parent,
                    'name' => $item->label,
                ];
            }
        }

        // Betöltés
        DB::table('settlements')->insert($settlements);
        DB::table('settlement_parts')->insert($parts);

        // AUTO_INCREMENT visszaállítása (ha szükséges)
        $maxId = max(array_keys($settlements));
        DB::statement("ALTER TABLE settlements AUTO_INCREMENT = " . ($maxId + 1));
        $maxPartId = max(array_column($parts, 'id'));
        DB::statement("ALTER TABLE settlement_parts AUTO_INCREMENT = " . ($maxPartId + 1));
    }

    protected function guessPostalCode(string $city): int
    {
        // Itt írj be logikát, ha tudod a települések irányítószámát.
        $postalCodes = [
            'Érd' => 2030,
            'Diósd' => 2049,
            'Tárnok' => 2461,
            'Százhalombatta' => 2440,
            'Törökbálint' => 2045,
            'Sóskút' => 2038,
        ];
        return $postalCodes[$city] ?? 9999;
    }
}
