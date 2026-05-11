<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_barcodes', function (Blueprint $table) {
            $table->string('scan_text')->nullable()->after('barcode_value');
        });
    }

    public function down(): void
    {
        Schema::table('admin_barcodes', function (Blueprint $table) {
            $table->dropColumn('scan_text');
        });
    }
};

