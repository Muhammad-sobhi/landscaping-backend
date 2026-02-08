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
    Schema::create('offers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
    $table->decimal('subtotal', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('total', 10, 2);
    $table->string('status')->default('draft'); // draft, sent, accepted, rejected
    $table->text('internal_notes')->nullable();
    $table->text('message_to_customer')->nullable();
    $table->timestamps();
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
