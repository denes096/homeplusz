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
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('storage_count')->default(0)->comment('Tárolók száma');
            $table->enum('storage_type', ['fixed', 'optional'])->default('optional')->comment('Tároló típusa: fixed=fixen hozzárendelt, optional=bármelyik választható');
            $table->boolean('is_required_storage')->default(false)->comment('Kötelező-e megvásárolni a tárolót');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['storage_count', 'storage_type', 'is_required_storage']);
        });
    }
};
