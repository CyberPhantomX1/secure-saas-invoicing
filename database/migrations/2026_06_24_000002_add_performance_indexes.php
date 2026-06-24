<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check and add indexes safely (only if they don't exist)
        
        // Invoices table indexes
        if (!$this->indexExists('invoices', 'invoices_user_id_status_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index(['user_id', 'status']);
            });
        }
        
        if (!$this->indexExists('invoices', 'invoices_user_id_invoice_date_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index(['user_id', 'invoice_date']);
            });
        }
        
        if (!$this->indexExists('invoices', 'invoices_status_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('status');
            });
        }

        // Customers table indexes
        if (!$this->indexExists('customers', 'customers_user_id_email_index')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index(['user_id', 'email']);
            });
        }
        
        if (!$this->indexExists('customers', 'customers_email_index')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->index('email');
            });
        }

        // Invoice items indexes
        if (!$this->indexExists('invoice_items', 'invoice_items_invoice_id_index')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->index('invoice_id');
            });
        }
    }

    public function down(): void
    {
        // Safe drop with checks
        if ($this->indexExists('invoices', 'invoices_user_id_status_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropIndex('invoices_user_id_status_index');
            });
        }
        
        if ($this->indexExists('invoices', 'invoices_user_id_invoice_date_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropIndex('invoices_user_id_invoice_date_index');
            });
        }
        
        if ($this->indexExists('invoices', 'invoices_status_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropIndex('invoices_status_index');
            });
        }

        if ($this->indexExists('customers', 'customers_user_id_email_index')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropIndex('customers_user_id_email_index');
            });
        }
        
        if ($this->indexExists('customers', 'customers_email_index')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropIndex('customers_email_index');
            });
        }

        if ($this->indexExists('invoice_items', 'invoice_items_invoice_id_index')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->dropIndex('invoice_items_invoice_id_index');
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            // For SQLite
            $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$indexName]);
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};