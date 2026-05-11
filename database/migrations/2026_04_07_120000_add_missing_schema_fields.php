<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'is_live')) {
                $table->boolean('is_live')->default(true)->after('is_open');
            }

            if (! Schema::hasColumn('vendors', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('status');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'harvest_date')) {
                $table->date('harvest_date')->nullable()->after('images');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('vendor_id');
            }
        });

        DB::table('vendors')->update([
            'is_live' => DB::raw('is_open'),
            'verification_status' => DB::raw('status'),
        ]);

        DB::table('orders')->update([
            'total_price' => DB::raw('total_amount'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'harvest_date')) {
                $table->dropColumn('harvest_date');
            }
        });

        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'is_live')) {
                $table->dropColumn('is_live');
            }

            if (Schema::hasColumn('vendors', 'verification_status')) {
                $table->dropColumn('verification_status');
            }
        });
    }
};
