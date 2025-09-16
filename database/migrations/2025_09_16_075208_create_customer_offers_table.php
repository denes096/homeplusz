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
        Schema::create('customer_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id'); // Match customers table id type
            $table->unsignedBigInteger('customer_search_id'); // Match customer_search table id type
            $table->json('property_ids'); // Array of property IDs sent in the offer
            $table->string('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customer_search_id')->references('id')->on('customer_search')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_offers');
    }
};
