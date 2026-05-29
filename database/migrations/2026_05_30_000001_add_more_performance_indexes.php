<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Disbursement screen filters by tenant + payment_method + status.
            $table->index(['tenant_id', 'payment_method', 'status'], 'orders_tenant_payment_status_idx');

            // Platform-wide reports filter by created_at without a tenant_id,
            // so the existing (tenant_id, created_at) composite can't be used.
            $table->index('created_at', 'orders_created_at_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            // Staff/owner listings filter by tenant + role.
            $table->index(['tenant_id', 'role_id'], 'users_tenant_role_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_tenant_payment_status_idx');
            $table->dropIndex('orders_created_at_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_tenant_role_idx');
        });
    }
};
