<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->timestamps();

            $table->index(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_services');
    }
};