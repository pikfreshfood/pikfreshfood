<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('shop_name');
            }

            if (! Schema::hasColumn('vendors', 'wallet_balance')) {
                $table->decimal('wallet_balance', 12, 2)->default(0)->after('rating');
            }

            if (! Schema::hasColumn('vendors', 'subscription_plan')) {
                $table->string('subscription_plan')->default('free')->after('wallet_balance');
            }

            if (! Schema::hasColumn('vendors', 'subscription_status')) {
                $table->string('subscription_status')->default('active')->after('subscription_plan');
            }

            if (! Schema::hasColumn('vendors', 'promo_video_url')) {
                $table->string('promo_video_url')->nullable()->after('subscription_status');
            }
        });

        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('completed');
                $table->string('reference')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wallet_transactions')) {
            Schema::dropIfExists('wallet_transactions');
        }

        Schema::table('vendors', function (Blueprint $table) {
            $columns = [];

            foreach (['profile_image', 'wallet_balance', 'subscription_plan', 'subscription_status', 'promo_video_url'] as $column) {
                if (Schema::hasColumn('vendors', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
