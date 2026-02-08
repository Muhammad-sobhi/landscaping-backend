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
        Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('job_id')->nullable()->constrained('jobs')->nullOnDelete();
    $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
    $table->string('invoice_number')->unique();
    $table->decimal('subtotal', 10, 2);
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('total', 10, 2);
    $table->string('payment_method')->nullable(); // cash, card, etc
    $table->string('status')->default('unpaid'); // unpaid, paid, cancelled
    $table->date('issued_at');
    $table->date('paid_at')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
