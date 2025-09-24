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
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // Match clients table id type
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('name', 100); // Kapcsolattartó neve
            $table->string('relationship', 50)->nullable(); // Kapcsolat (pl. feleség, testvér, stb.)
            $table->string('phone', 100)->nullable(); // Telefonszám
            $table->string('email', 100)->nullable(); // Email cím
            $table->text('notes')->nullable(); // Megjegyzések
            $table->boolean('is_primary')->default(false); // Elsődleges kapcsolattartó-e
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contacts');
    }
};
