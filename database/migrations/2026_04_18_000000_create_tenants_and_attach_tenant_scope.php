<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('address')->nullable();
            $table->string('primary_color')->default('#ff7a18');
            $table->string('secondary_color')->default('#111827');
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $defaultTenantId = DB::table('tenants')->insertGetId([
            'name' => 'Happy Fried',
            'slug' => 'happyfried',
            'tagline' => 'Crispy kitchen for every table.',
            'description' => 'Tenant default untuk outlet utama Happy Fried.',
            'contact_phone' => '62895363076706',
            'contact_email' => 'hello@happyfried.test',
            'address' => 'Main Outlet',
            'primary_color' => '#ff7a18',
            'secondary_color' => '#111827',
            'hero_title' => 'Pesan lebih cepat, masak lebih rapi, monitor outlet dari satu dashboard.',
            'hero_subtitle' => 'Satu basis aplikasi untuk banyak outlet, tetap ringan dipakai kasir, kitchen, dan customer.',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });

        DB::table('users')->update(['tenant_id' => $defaultTenantId]);
        DB::table('categories')->update(['tenant_id' => $defaultTenantId]);
        DB::table('items')->update(['tenant_id' => $defaultTenantId]);
        DB::table('orders')->update(['tenant_id' => $defaultTenantId]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::dropIfExists('tenants');
    }
};
