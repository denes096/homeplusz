<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path=database_path('sql/customers.sql');
        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            throw new \Exception("SQL file not found: $path");
        }
        //
        Schema::create('customer_search', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->text('search');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('customer_search');
    }
};
