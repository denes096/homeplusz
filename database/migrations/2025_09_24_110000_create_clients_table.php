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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Megbízó neve');
            $table->string('company')->nullable()->comment('Cég neve');
            $table->string('email')->nullable()->comment('Email cím');
            $table->string('phone')->nullable()->comment('Telefonszám');
            $table->string('mobile')->nullable()->comment('Mobil szám');
            $table->string('address')->nullable()->comment('Cím');
            $table->string('city')->nullable()->comment('Város');
            $table->string('zip_code')->nullable()->comment('Irányítószám');
            $table->string('country', 100)->default('Magyarország')->comment('Ország');
            $table->string('contact_person')->nullable()->comment('Kapcsolattartó személy neve');
            $table->string('contact_position')->nullable()->comment('Kapcsolattartó beosztása');
            $table->text('notes')->nullable()->comment('Megjegyzések');
            $table->string('status', 20)->default('Aktív')->comment('Státusz');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Referens felhasználó');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
