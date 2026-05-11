<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_invites', function (Blueprint $table) {
            $table->json('offer_sdp')->nullable()->after('status');
            $table->json('answer_sdp')->nullable()->after('offer_sdp');
            $table->json('buyer_candidates')->nullable()->after('answer_sdp');
            $table->json('vendor_candidates')->nullable()->after('buyer_candidates');
            $table->timestamp('accepted_at')->nullable()->after('vendor_candidates');
            $table->timestamp('ended_at')->nullable()->after('accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('call_invites', function (Blueprint $table) {
            $table->dropColumn([
                'offer_sdp',
                'answer_sdp',
                'buyer_candidates',
                'vendor_candidates',
                'accepted_at',
                'ended_at',
            ]);
        });
    }
};
