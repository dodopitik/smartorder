<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Snapshot of the per-order platform fee at the moment this order
            // was created. Future changes to the global setting must NEVER
            // alter this value, so historical billing stays correct.
            $table->unsignedInteger('platform_fee')->default(0)->after('grandtotal');
        });

        // Backfill existing orders with the current platform fee setting so
        // billing/dashboard/disbursement views keep showing meaningful totals.
        $currentFee = (int) AppSetting::getValue('monthly_fee_per_order', '1000');

        DB::table('orders')->update(['platform_fee' => $currentFee]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('platform_fee');
        });
    }
};
