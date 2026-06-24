<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->unique()->after('status');
            $table->timestamp('paid_at')->nullable()->after('stripe_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique(['stripe_session_id']);
            $table->dropColumn(['stripe_session_id', 'paid_at']);
        });
    }
};