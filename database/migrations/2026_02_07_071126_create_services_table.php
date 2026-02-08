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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., Tree Care & Removal
            $table->text('description'); 
            $table->string('icon')->nullable(); // SVG name or icon class
            $table->json('tags'); // Stores ["Pruning", "Removal", "Health Assessment"]
            $table->string('image_path'); // For the arborist photo on the right
            $table->integer('order')->default(0); // To control display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
