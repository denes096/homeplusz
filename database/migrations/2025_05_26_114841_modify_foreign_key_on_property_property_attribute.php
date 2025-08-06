<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_property_attribute', function (Blueprint $table) {
            // Először le kell dobni a meglévő foreign key-t (ha tudjuk a nevét)
            $table->dropForeign(['property_id']);
        });

        Schema::table('property_property_attribute', function (Blueprint $table) {
            // Újra hozzáadjuk CASCADE-del
            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('property_property_attribute', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('restrict'); // vagy set null, attól függ az eredetitől
        });
    }
};
