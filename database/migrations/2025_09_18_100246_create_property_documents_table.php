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
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Document name (e.g., "Szerződés", "Megrendelő", "Vételi", "Szemle")
            $table->string('category'); // Document category
            $table->string('file_path'); // Path to the uploaded file
            $table->string('original_name'); // Original filename
            $table->string('file_type'); // MIME type
            $table->integer('file_size'); // File size in bytes
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();

            // Index for better performance
            $table->index(['property_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
