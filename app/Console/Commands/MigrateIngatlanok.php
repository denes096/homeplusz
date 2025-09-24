<?php

namespace App\Console\Commands;

use App\Models\Label;
use App\Models\Property;
use App\Models\PropertyAttribute;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use App\Models\UniqueCode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateIngatlanok extends Command
{
    protected $signature = 'migrate:ingatlanok';

    protected $description = 'Migrálja a régi ingatlanokat az új struktúrába';

    public function handle()
    {
        // $this->migrateCustomers();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $id = DB::table('property_attribute_categories')->insertGetId(
            [
                'name' => 'Épület / Lakás',
            ],
        );

        $this->migrateDetails();
        $this->migratePropTypes();

        DB::transaction(function () {
            // 1. Lekérjük az összes varos típusú rekordot
            $rows = DB::connection('old')->table('torzsadat')
                ->where('datatype', 'varos')
                ->orderBy('parent')
                ->orderBy('sort')
                ->get();

            $settlementMap = [];

            foreach ($rows as $row) {
                if ($row->parent == 0) {
                    // Város létrehozása
                    $settlement = Settlement::create([
                        'id' => $row->Id,
                        'postal_code' => $row->Id, // Ha nincs irányítószám, ideiglenes dummy kód
                        'name' => $row->label,
                        'county' => 'XXX megye', // opcionális, ha ismered
                    ]);

                    $settlementMap[$row->Id] = $settlement->id;
                }
            }

            // 2. Most a településrészek (parent > 0)
            foreach ($rows as $row) {
                if ($row->parent > 0 && isset($settlementMap[$row->parent])) {
                    SettlementPart::create([
                        'id' => $row->Id,
                        'settlement_id' => $settlementMap[$row->parent],
                        'name' => $row->label,
                    ]);
                }
            }
        });
        $this->info('Ingatlanok migráció indítása...');

        $oldIngatlanok = DB::connection('old')->table('ingatlanok')->orderBy('Id')
            //->limit(20)
            ->get();
        $skipped = 0;

        $lastCode = 0;
        foreach ($oldIngatlanok as $index => $row) {
            $this->info($row->Id);
            // Ha már létezik az adott property_code, ugorjuk át
            if (! $row->ingatlankod) {
                $this->warn("Hiányzó ingatlankód az ID: {$row->Id} rekordnál, kihagyva.");
                $skipped++;

                continue;
            }

            $lastCode = preg_replace('/[^\d]/', '', $row->ingatlankod);

            $attributeValues = [
                'epulet_lakoegysegek_szama' => $row->epulet_lakoegysegek_szama,
                'epulet_lakoszintek_szama' => $row->epulet_lakoszintek_szama,
                'epulet_epuletszintek_szama' => $row->epulet_epuletszintek_szama,
                'epulet_szobaszam' => $row->epulet_szobaszam,
                'epulet_felszobaszam' => $row->epulet_felszobaszam,
                'epulet_lakotermeret' => $row->epulet_lakotermeret,
                'emelet' => $row->emelet,
                'epulet_epites_eve' => $row->epulet_epites_eve,
                'epulet_felujitas_eve' => $row->epulet_felujitas_eve,

                'epulet_allapot_kivul' => $row->epulet_allapot_kivul,
                'epulet_allapot_belul' => $row->epulet_allapot_belul,
                'epulet_energetika' => $row->epulet_energetika,
                'epulet_komfort' => $row->epulet_komfort,

                // 'falazat'...
                // futes...

                // 'cserealap' => $row->cserealap,
                //                'kiado_butorozott' => $row->kiado_butorozott === 'Igen' ? '1' : '0',
                //                'kozmu_gaz' => $row->kozmu_gaz,
                //                'kozmu_villany' => $row->kozmu_villany,
                //                'kozmu_viz' => $row->kozmu_viz,
                //                'kozmu_csatorna' => $row->kozmu_csatorna,
                //                'kozmu_kabeltv' => $row->kozmu_kabeltv,
                //                'kozmu_internet' => $row->kozmu_internet,
                //                'parkolas' => $row->parkolas,
                //                'tomegkozlekedes' => $row->tomegkozlekedes,
                //                'telek_alapterulet' => $row->telek_alapterulet,
                //                'telek_domborzat' => $row->telek_domborzat,
                //                'haziallat' => $row->haziallat === 'Igen' ? '1' : '0',
                //                'butorozott' => $row->butorozott === 'Igen' ? '1' : '0',
            ];

            $attributes = PropertyAttribute::all()->keyBy('name');

            foreach ($attributeValues as $name => $value) {
                // Csak, ha az attribútum létezik, és van érték
                if (isset($attributes[$name]) && $value !== null) {
                    DB::table('property_property_attribute')->updateOrInsert(
                        [
                            'property_id' => $row->Id,
                            'property_attribute_id' => $attributes[$name]->id,
                        ],
                        ['value' => (string) $value]
                    );
                }
            }

            // Adatleképezés
            $adType = match (true) {
                $row->elado === 'Igen' && $row->kiado === 'Igen' => 'sell_and_rent',
                $row->elado === 'Igen' => 'sell',
                $row->kiado === 'Igen' => 'rent',
                default => 'sell'
            };

            // képek összegyűjtése JSON tömbként
            $images = DB::connection('old')->table('ingatlanok_kepei')
                ->where('ingatlanId', $row->Id)
                ->orderBy('sort')
                ->pluck('filepath')
                ->filter() // kiszedi a null értékeket
                ->values()
                ->toArray();

            $imageJson = count($images) > 0 ? json_encode($images) : null;

            $baseImageUrl = 'https://www.otthonplusz.hu/pictures/';

            //            foreach ($images as $filename) {
            //                try {
            //                    $url = $baseImageUrl . $filename;
            //
            //                    $response = Http::timeout(10)->get($url);
            //
            //                    if ($response->successful()) {
            //                        Storage::disk('public')->put("uploads/{$row->Id}/{$filename}", $response->body());
            //                        $this->info($index . " ingatlan képe letöltve");
            //                    } else {
            //                        $this->warn("Nem sikerült letölteni a képet: {$url}");
            //                        logger()->warning("Nem sikerült letölteni a képet: {$url}");
            //                    }
            //                } catch (\Exception $e) {
            //                    logger()->error("Hiba a {$url} letöltésekor: " . $e->getMessage());
            //                }
            //            }

            // új Property rekord létrehozása
            $property = new Property([
                'id' => $row->Id,
                'featured' => false,

                'description' => $row->leiras,
                'short_description' => $row->rovid_leiras,
                'images' => $imageJson,

                'is_active' => $row->status === 'Aktív',
                'ad_type' => $adType,
                'property_code' => $row->ingatlankod,
                'title' => $row->cimsor ?? '',

                'price' => $row->ar ?? 0,
                'old_price' => $row->regiar,
                'price_reduction_date' => $row->arcsokkenes_datum ? Carbon::createFromTimestamp($row->arcsokkenes_datum)->format('Y-m-d H:i:s') : null,

                'rental_price' => $row->berleti_dij,
                'old_rental_price' => $row->regiberleti_dij,
                'rental_price_reduction_date' => $row->bercsokkenes_datum ? Carbon::createFromTimestamp($row->bercsokkenes_datum)->format('Y-m-d H:i:s') : null,
                'rental_utilities_included' => $row->berlet_rezsi === 'Igen' ? true : false,
                'rental_deposit' => $row->kaucio,

                'internal_note' => $row->belso_megjegyzes,

                'settlement_id' => $row->varos ?? 1, // fallback, ha NULL
                'settlement_part_id' => $row->varosresz,
                'property_type_id' => 1, // majd később állítsd be vagy térképezd
                'property_subtype_id' => null,
                'project_id' => null,
                'user_id' => $row->createuser ?? 1, // ha nincs owner, fallback

                'created_at' => $row->createtime ? now()->setTimestamp($row->createtime) : now(),
                'updated_at' => $row->modifytime ? now()->setTimestamp($row->modifytime) : now(),
            ]);

            $property->save();

        }

        $this->migrateExtra();

        $propattrsfromtable = $this->migratePropTableColsThatNotBelongsToIt();

        $propattrsfromtable = array_combine(
            array_column(
                $propattrsfromtable,
                'name'
            ),
            $propattrsfromtable
        );

        $rows = [];
        foreach ($oldIngatlanok as $index => $row) {
            /**
             * @var $name
             * @var PropertyAttribute $propattr
             */
            foreach ($propattrsfromtable as $name => $propattr) {
                if ($name === 'futes') {
                }

                if (! property_exists($row, $name)) {

                    continue;
                }
                $value = $row->{$name};
                if ($value == 'Igen') {
                    $value = 1;
                } elseif ($value == 'Nem') {
                    $value = 0;
                }
                $rows[] = [
                    'property_id' => $row->Id,
                    'property_attribute_id' => $propattr->id,
                    'value' => $value,
                ];
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('property_property_attribute')->insert($chunk);
        }

        UniqueCode::create([
            'code' => $lastCode,
        ]);

        PropertyAttribute::where('show_in_search', 1)->update([
            'show_in_search' => 0,
        ]);

        PropertyAttribute::whereNotIn('name', ['epulet_lakotermeret'])->update(['show_in_search' => 1]);

        $this->info("Migráció kész. Kihagyott rekordok: $skipped db.");
    }

    public function migrateDetails() {}

    public function migrateCustomers()
    {
        $path = database_path('sql/customers.sql');
        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            throw new \Exception("SQL file not found: $path");
        }
    }

    public function migratePropTableColsThatNotBelongsToIt()
    {
        $extrak = DB::connection('old')->table('torzsadat')->where('datatype', '=', 'allapot')->get();

        $datas = [

        ];
        // 3. Átalakítás és mentés
        foreach ($extrak as $extra) {
            $datas[$extra->Id] = $extra->label;
        }

        $propAttrsFromTable = [];
        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'emelet',
            'label' => 'Emelet',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_lakoszintek_szama',
            'label' => 'Lakószintek száma',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_epuletszintek_szama',
            'label' => 'Épület szintjeinek száma',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);
        //
        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_lakoegysegek_szama',
            'label' => 'Lakóegységek száma',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_lakotermeret',
            'label' => 'Lakótér méret (m²)',
            'type' => 'number',
            'suffix' => 'm²',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_szobaszam',
            'label' => 'Szobák száma',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_felszobaszam',
            'label' => 'Félszobák száma',
            'type' => 'number',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_allapot_kivul',
            'label' => 'Épület állapota (külső)',
            'type' => 'select',
            'values' => json_encode($datas),
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_allapot_belul',
            'label' => 'Épület állapota (belső)',
            'type' => 'select',
            'values' => json_encode($datas),
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_epites_eve',
            'label' => 'Építés éve',
            'type' => 'number',
            'suffix' => 'év',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_felujitas_eve',
            'label' => 'Felújítás éve',
            'type' => 'number',
            'suffix' => 'év',
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_energetika',
            'label' => 'Energetikai besorolás',
            'type' => 'select',
            'values' => json_encode([
                '67' => 'Még nem készült',
                '68' => 'A+++',
                '69' => 'A++',
                '70' => 'A+',
                '71' => 'A',
                '72' => 'B',
                '73' => 'C',
                '74' => 'D',
                '75' => 'E',
                '76' => 'F',
                '77' => 'G',
                '161' => 'H',
                '162' => 'J',
            ]),
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'epulet_komfort',
            'label' => 'Komfort fokozat',
            'type' => 'select',
            'values' => json_encode([
                '103' => 'Luxus',
                '104' => 'Duplakomfortos',
                '105' => 'Összkomfortos',
                '106' => 'Komfortos',
                '107' => 'Félkomfortos',
                '108' => 'Komfort nélküli',
                '206' => 'Triplakomfort',
            ]),
            'property_attribute_category_id' => 1,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'cserealap',
            'label' => 'Cserealap megadva',
            'type' => 'checkbox',
            'property_attribute_category_id' => 2,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kiado_butorozott',
            'label' => 'Kiadóként bútorozott',
            'type' => 'checkbox',
            'property_attribute_category_id' => 2,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_gaz',
            'label' => 'Gáz közmű',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_villany',
            'label' => 'Villany közmű',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_viz',
            'label' => 'Víz közmű',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_csatorna',
            'label' => 'Csatorna közmű',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_kabeltv',
            'label' => 'Kábel TV',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'kozmu_internet',
            'label' => 'Internet elérhetőség',
            'type' => 'select',
            'values' => json_encode([
                '43' => 'Bekötve',
                '44' => 'Telken belül',
                '45' => 'Utcában',
                '46' => 'Nincs',
            ]),
            'property_attribute_category_id' => 4,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'parkolas',
            'label' => 'Parkolás',
            'type' => 'select',
            'values' => json_encode([
                '47' => 'Dupla garázs',
                '48' => 'Garázs',
                '49' => 'Fedett gk. beálló',
                '50' => 'Gk. beálló',
                '51' => 'Utcán/közterületen',
                '113' => 'Garázs kialakítható',
                '208' => 'Teremgarázs',
            ]),
            'property_attribute_category_id' => 5,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'tomegkozlekedes',
            'label' => 'Tömegközlekedés',
            'type' => 'select',
            'values' => json_encode([
                '52' => 'Kiváló',
                '53' => 'Jó',
                '54' => 'Közepes',
                '55' => 'Elégséges',
                '56' => 'Rossz',
            ]),
            'property_attribute_category_id' => 5,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'telek_alapterulet',
            'label' => 'Telek alapterület',
            'type' => 'number',
            'suffix' => 'm²',
            'property_attribute_category_id' => 5,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'telek_domborzat',
            'label' => 'Domborzat',
            'type' => 'select',
            'values' => json_encode([
                '-1' => 'Ismeretlen',
                '0' => 'Sík',
                '1' => 'Lejtős',
            ]),
            'property_attribute_category_id' => 5,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'haziallat',
            'label' => 'Háziállat hozható',
            'type' => 'checkbox',
            'property_attribute_category_id' => 2,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'butorozott',
            'label' => 'Bútorozott',
            'type' => 'checkbox',
            'property_attribute_category_id' => 2,
        ]);

        $propAttrsFromTable[] = PropertyAttribute::create([
            'name' => 'futes',
            'label' => 'Fűtés',
            'type' => 'select_multiple',
            'values' => json_encode([
                '88' => 'Gáz-cirkó',
                '89' => 'Gázkonvektor',
                '90' => 'Gázkazán',
                '91' => 'Gázhéra',
                '92' => 'Vegyestüzelésű kazán',
                '93' => 'Cserépkályha',
                '94' => 'Kandalló',
                '95' => 'Távfűtés',
                '96' => 'Házközponti',
                '97' => 'Elektromos',
                '98' => 'Geotermikus',
                '99' => 'Napkollektor',
                '100' => 'Fan-coil',
                '101' => 'Hőszivattyú',
                '102' => 'Kályha',
                '157' => 'Egyéb',
                '167' => 'Kondenzációs gáz-cirkó',
                '198' => 'Elektromos-cirkó',
                '209' => 'Napelem',
                '210' => 'Vízteres kandalló',
                '211' => 'Infra',
                '230' => 'Klíma',
                '235' => 'Fatüzelésű kazán',
                '248' => 'Padlófűtés',
                '249' => 'Norvég fűtőpanel',
                '271' => 'Mennyezet hűtés-fűtés',
            ]),
            'property_attribute_category_id' => 2,
        ]);

        return $propAttrsFromTable;
    }

    public function migrateExtra()
    {
        //
        $categories = [
            'extra' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'extra',
                'description' => 'Extrák',
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
            'kozmu' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'kozmu',
                'description' => 'Közművek',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'telek' => DB::table('property_attribute_categories')->insertGetId([
                'name' => 'telek',
                'description' => 'Telek Jellemzők',
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
            if (! isset($categories[$category])) {
                continue;
            }

            DB::connection('mariadb')->table('property_attributes')->insert([
                'id' => $extra->Id,
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

        // prefill
        $attributes = PropertyAttribute::all(['id']);
        $properties = Property::all(['id']);
        $rows = [];
        foreach ($properties as $property) {
            foreach ($attributes as $attribute) {
                $rows[] = [
                    'property_id' => $property->id,
                    'property_attribute_id' => $attribute->id,
                    'value' => 0,
                ];
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('property_property_attribute')->insert($chunk);
        }

        foreach ($extrak as $extra) {
            $category = $extra->category;

            if ($category == 'cimke') {
                Label::create([
                    'id' => $extra->Id,
                    'name' => $extra->label ?? 'n/a',
                    'color' => $extra->value,
                    'filter' => 0,
                ]);

                $ingextrak = DB::connection('old')->table('ingatlanok_extrai')->where('extraId', '=', $extra->Id)->get();
                foreach ($ingextrak as $ingextrak) {
                    DB::table('label_property')->insert([
                        'label_id' => $extra->Id,
                        'property_id' => $ingextrak->ingatlanId,
                    ]);
                }

            } else {
                $ingextrak = DB::connection('old')->table('ingatlanok_extrai')->where('extraId', '=', $extra->Id)->get();
                foreach ($ingextrak as $ingextrak) {
                    DB::table('property_property_attribute')->insert([
                        'property_id' => $ingextrak->ingatlanId,
                        'property_attribute_id' => $ingextrak->extraId,
                        'value' => 1,
                    ]);
                }
            }
        }
    }

    public function migratePropTypes()
    {
        $types = DB::connection('old')->table('torzsadat')->where('datatype', '=', 'ingatlantipus')->where('parent', '=', 0)->orderBy('sort')->get();

        foreach ($types as $type) {
            PropertyType::create([
                'id' => $type->Id,
                'name' => $type->label,
            ]);
        }

        $subTypes = DB::connection('old')->table('torzsadat')->where('datatype', '=', 'ingatlantipus')->where('parent', '!=', 0)->orderBy('sort')->get();
        foreach ($subTypes as $type) {
            PropertySubtype::create([
                'id' => $type->Id,
                'name' => $type->label,
                'property_type_id' => $type->parent,
            ]);
        }

    }

    public function migrateTorzsadat($dataType, $isMultiselect) {}
}
