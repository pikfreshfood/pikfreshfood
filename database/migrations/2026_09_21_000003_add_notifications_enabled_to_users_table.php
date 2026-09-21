<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'notifications_enabled')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('notifications_enabled')->default(false)->after('preferences');
            });
        }

        if (Schema::hasTable('push_tokens')) {
            DB::table('users')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('push_tokens')
                        ->whereColumn('push_tokens.user_id', 'users.id');
                })
                ->update(['notifications_enabled' => true]);
        }

        if (Schema::hasTable('push_subscriptions')) {
            DB::table('users')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('push_subscriptions')
                        ->whereColumn('push_subscriptions.user_id', 'users.id');
                })
                ->update(['notifications_enabled' => true]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'notifications_enabled')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('notifications_enabled');
            });
        }
    }
};
