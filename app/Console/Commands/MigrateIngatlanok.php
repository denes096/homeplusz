<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Property;
use Carbon\Carbon;
use App\Models\Settlement;
use App\Models\SettlementPart;

class MigrateIngatlanok extends Command
{
    protected $signature = 'migrate:ingatlanok';
    protected $description = 'Migrálja a régi ingatlanokat az új struktúrába';

    public function handle()
    {
        DB::transaction(function () {
            // 1. Lekérjük az összes varos típusú rekordot
            $rows = DB::table('torzsadat')
                ->where('datatype', 'varos')
                ->orderBy('parent')
                ->orderBy('sort')
                ->get();

            $settlementMap = [];

            foreach ($rows as $row) {
                if ($row->parent == 0) {
                    // Város létrehozása
                    $settlement = Settlement::create([
                        'postal_code' => 9999, // Ha nincs irányítószám, ideiglenes dummy kód
                        'name' => $row->label,
                        'part' => null,
                        'county' => 'Pest megye', // opcionális, ha ismered
                        'area' => 'ismeretlen', // opcionális
                    ]);

                    $settlementMap[$row->Id] = $settlement->id;
                }
            }

            // 2. Most a településrészek (parent > 0)
            foreach ($rows as $row) {
                if ($row->parent > 0 && isset($settlementMap[$row->parent])) {
                    SettlementPart::create([
                        'settlement_id' => $settlementMap[$row->parent],
                        'name' => $row->label,
                    ]);
                }
            }
        });
        die();
        $this->info('Ingatlanok migráció indítása...');

        $oldIngatlanok = DB::connection('old')->table('ingatlanok')->get();
        $skipped = 0;

        foreach ($oldIngatlanok as $row) {
            // Ha már létezik az adott property_code, ugorjuk át
            if (!$row->ingatlanKod) {
                $this->warn("Hiányzó ingatlankód az ID: {$row->Id} rekordnál, kihagyva.");
                $skipped++;
                continue;
            }

            // Adatleképezés
            $adType = match (true) {
                $row->elado === 'Igen' => 'sell',
                $row->kiado === 'Igen' => 'rent',
                default => 'buy',
            };

            $price = match ($adType) {
                'sell' => $row->ar,
                'rent' => $row->berleti_dij,
                default => null,
            };

            $property = new Property();
            $property->id = $row->Id;
            $property->title = $row->cimsor ?: '(Nincs cím)';
            $property->description = $row->leiras ?: '';
            $property->ad_type = $adType;
            $property->price = $price ?: 0;
            $property->property_code = substr($row->ingatlanKod, 0, 10);
            $property->settlement_id = $row->varos ?: 1; // default város, ha nincs
            $property->settlement_part_id = $row->varosresz ?: null;
            $property->user_id = $row->createuser ?: 1; // default user ID
            $property->property_type_id = 1; // ide fix érték mehet vagy külön mappa
            $property->project_id = null;
            $property->featured = false;

            // Időbélyegek konvertálása
            $property->created_at = $row->createtime ? Carbon::createFromTimestamp($row->createtime) : now();
            $property->updated_at = $row->modifytime ? Carbon::createFromTimestamp($row->modifytime) : now();

            // Soft delete, ha inaktív vagy archivált
            if ($row->status === 'Archív' || $row->active === 'Nem') {
                $property->deleted_at = now();
            }

            $property->save();
        }

        $this->info("Migráció kész. Kihagyott rekordok: $skipped db.");
    }
}

