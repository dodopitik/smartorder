<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('items', 'description')) {
            Schema::table('items', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });

            return;
        }

        Schema::table('items', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('items', 'description')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
