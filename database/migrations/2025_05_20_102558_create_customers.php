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
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('status', 20)->default('Aktív');
            $table->integer('refId')->nullable();
            $table->integer('kategoria')->default(1);
            $table->string('ekod', 20);
            $table->string('name_0', 100);
            $table->string('phone_0', 100)->nullable();
            $table->string('azonosito1_0', 100)->nullable();
            $table->string('azonosito2_0', 100)->nullable();
            $table->string('name_1', 100)->nullable();
            $table->string('phone_1', 100)->nullable();
            $table->string('name_2', 100)->nullable();
            $table->string('phone_2', 100)->nullable();
            $table->string('name_3', 100)->nullable();
            $table->string('phone_3', 100)->nullable();
            $table->string('name_4', 100)->nullable();
            $table->string('phone_4', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('note', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
