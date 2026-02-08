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
        Schema::create('jobs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
    $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
    $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
    $table->date('scheduled_date')->nullable();
    $table->date('completed_date')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
