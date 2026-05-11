<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peer_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('callee_id')->constrained('users')->cascadeOnDelete();
            $table->string('room_name');
            $table->string('call_type')->default('audio');
            $table->string('status')->default('ringing');
            $table->json('offer_sdp')->nullable();
            $table->json('answer_sdp')->nullable();
            $table->json('caller_candidates')->nullable();
            $table->json('callee_candidates')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peer_calls');
    }
};
