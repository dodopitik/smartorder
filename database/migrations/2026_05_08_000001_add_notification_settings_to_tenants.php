<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->boolean('notify_on_new_order')->default(true)->after('is_active');
            $table->text('notification_emails')->nullable()->after('notify_on_new_order');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['notify_on_new_order', 'notification_emails']);
        });
    }
};
