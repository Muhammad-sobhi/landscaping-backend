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
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            // Link to the offer
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            
            // Item details
            $table->string('name'); // e.g., "Oak Tree", "Labor Fee"
            $table->string('category'); // e.g., "material", "service", "equipment"
            
            // Financials
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2); // Calculated as qty * unit_price
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_items');
    }
};
