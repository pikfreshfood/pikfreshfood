<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'boosted_until')) {
                $table->timestamp('boosted_until')->nullable()->after('subscription_expires_at');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'boosted_until')) {
                $table->timestamp('boosted_until')->nullable()->after('is_available');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'boosted_until')) {
                $table->dropColumn('boosted_until');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'boosted_until')) {
                $table->dropColumn('boosted_until');
            }
        });
    }
};

