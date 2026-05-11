<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_chat_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable()->index();
            $table->string('session_token')->nullable()->index();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('support_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('support_chat_threads')->cascadeOnDelete();
            $table->string('sender_type', 20);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_name');
            $table->text('message');
            $table->boolean('is_read_by_admin')->default(false);
            $table->boolean('is_read_by_client')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_chat_messages');
        Schema::dropIfExists('support_chat_threads');
    }
};
