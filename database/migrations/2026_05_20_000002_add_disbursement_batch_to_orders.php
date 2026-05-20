<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Group every "Tandai sudah disetor" action so the super admin can
            // undo only the most recent batch instead of resetting an entire
            // tenant's disbursement history at once.
            $table->string('disbursement_batch_id', 32)->nullable()->after('disbursed_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('disbursement_batch_id');
        });
    }
};
