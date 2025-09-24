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
        Schema::create('partner_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id'); // Match partners table id type
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->string('name')->nullable(); // Dokumentum neve
            $table->string('category', 50)->nullable(); // Kategória (szerződés, megrendelő, stb.)
            $table->string('file_path'); // Fájl elérési útja
            $table->string('original_name'); // Eredeti fájlnév
            $table->string('file_type', 100)->nullable(); // MIME típus
            $table->integer('file_size')->nullable(); // Fájlméret byte-ban
            $table->text('description')->nullable(); // Leírás
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_documents');
    }
};
