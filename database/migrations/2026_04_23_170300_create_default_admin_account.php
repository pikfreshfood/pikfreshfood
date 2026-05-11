<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $existing = DB::table('users')
            ->where(function ($query) {
                $query->where('name', 'admin')
                    ->orWhere('email', 'admin@pikfreshfood.local');
            })
            ->first();

        $payload = [
            'name' => 'admin',
            'email' => 'admin@pikfreshfood.local',
            'password' => Hash::make('admin'),
            'role' => DB::getDriverName() === 'sqlite' ? 'buyer' : 'admin',
            'admin_role' => 'super_admin',
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update($payload);
            return;
        }

        DB::table('users')->insert($payload + [
            'created_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')
            ->whereNotNull('admin_role')
            ->where('name', 'admin')
            ->where('email', 'admin@pikfreshfood.local')
            ->delete();
    }
};
