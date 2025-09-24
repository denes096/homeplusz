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
        Schema::create('property_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->integer('parent_id')->nullable();
            $table->string('short_label')->nullable();
            $table->foreignId('property_attribute_category_id')->constrained();
            $table->enum('type', ['text', 'checkbox', 'select', 'number', 'radio', 'select_multiple'])->default('text');
            $table->json('values')->nullable();
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('show_in_search')->default(true);
            $table->boolean('show_in_list')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_attributes');
    }
};
