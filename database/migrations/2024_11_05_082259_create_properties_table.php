<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->boolean('featured')->default(false);

            // Leírások
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();

            $table->text('images')->nullable();

            // status Aktív/Felfüggesztve
            $table->boolean('is_active')->default(true);
            // elado Igen/Nem
            // kiado Igen/Nem
            $table->enum('ad_type', ['sell', 'rent', 'sell_and_rent'])->default('sell');
            // ingatlankod
            $table->string('property_code', 10)->unique()->nullable();
            // cimsor
            $table->text('title');
            $table->text('inner_comments')->nullable();
            // ar
            $table->float('price');
            // regiar
            $table->float('old_price')->nullable();
            // arcsokkentes_datum
            $table->date('price_reduction_date')->nullable();

            // Bérleti díjak
            $table->float('rental_price')->nullable();
            $table->float('old_rental_price')->nullable();
            $table->date('rental_price_reduction_date')->nullable();
            $table->boolean('rental_utilities_included')->nullable();
            $table->integer('rental_deposit')->nullable();

            // belsomegjegyzes
            $table->text('internal_note')->nullable();

            $table->foreignId('settlement_id')->constrained();
            $table->foreignId('settlement_part_id')->nullable()->constrained();
            $table->foreignId('property_type_id')->constrained();
            $table->foreignId('property_subtype_id')->nullable()->constrained();
            $table->foreignId('project_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * CREATE TABLE IF NOT EXISTS `ingatlanok` (

     * `emelet` int(11) DEFAULT NULL,
     * `varos` int(11) DEFAULT NULL,
     * `varosresz` int(11) DEFAULT NULL,
     * `epulet_lakoszintek_szama` int(11) DEFAULT '1',
     * `epulet_epuletszintek_szama` int(11) DEFAULT NULL,
     * `epulet_lakoegysegek_szama` int(11) DEFAULT '1',
     * `epulet_lakotermeret` int(11) DEFAULT NULL,
     * `epulet_szobaszam` int(11) DEFAULT NULL,
     * `epulet_felszobaszam` int(11) DEFAULT NULL,
     * `epulet_allapot_kivul` int(11) DEFAULT '-1',
     * `epulet_allapot_belul` int(11) DEFAULT '-1',
     * `epulet_epites_eve` int(11) DEFAULT NULL,
     * `epulet_felujitas_eve` int(11) DEFAULT NULL,
     * `epulet_energetika` int(11) DEFAULT '-1',
     * `epulet_komfort` int(11) DEFAULT '-1',
     * `cserealap` int(11) DEFAULT NULL,
     * `kiado_butorozott` set('Igen','Nem') COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'Nem',
     * `kozmu_gaz` int(11) DEFAULT '-1',
     * `kozmu_villany` int(11) DEFAULT '-1',
     * `kozmu_viz` int(11) DEFAULT '-1',
     * `kozmu_csatorna` int(11) DEFAULT '-1',
     * `kozmu_kabeltv` int(11) DEFAULT '-1',
     * `kozmu_internet` int(11) DEFAULT '-1',
     * `parkolas` int(11) DEFAULT '-1',
     * `tomegkozlekedes` int(11) DEFAULT '-1',
     * `telek_alapterulet` int(11) DEFAULT NULL,
     * `telek_domborzat` int(11) DEFAULT '-1',
     * `haziallat` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
     * `butorozott` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,

     * )
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
