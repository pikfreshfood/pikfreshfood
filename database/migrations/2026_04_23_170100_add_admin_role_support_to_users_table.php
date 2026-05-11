<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'admin_role')) {
                $table->string('admin_role')->nullable()->after('role');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('buyer','vendor','admin') NOT NULL DEFAULT 'buyer'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('buyer','vendor') NOT NULL DEFAULT 'buyer'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'admin_role')) {
                $table->dropColumn('admin_role');
            }
        });
    }
};

