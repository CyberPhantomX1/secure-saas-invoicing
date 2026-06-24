<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->string('invoice_number', 50);
            $table->date('invoice_date');
            $table->date('due_date');

            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 14, 2)->default(0.00);

            $table->string('status', 20)->default('draft');

            $table->timestamps();

            $table->unique(['user_id', 'invoice_number']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};