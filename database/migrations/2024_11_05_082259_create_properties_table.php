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

            $table->text('title');
            $table->longText('description');

            $table->enum('ad_type', ['sell', 'rent', 'buy'])->default('sell');
            $table->float('price');
            $table->text('images')->nullable();
            $table->string('property_code', 10)->unique()->nullable();

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
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
