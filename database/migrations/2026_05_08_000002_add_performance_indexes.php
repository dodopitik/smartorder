<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->index(['tenant_id', 'is_available']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'created_at']);
            $table->dropIndex(['tenant_id', 'status']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'is_available']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
        });
    }
};
