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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('shop_name');
            $table->text('description')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('id_document')->nullable(); // path to uploaded ID
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('total_orders')->default(0);
            $table->time('opening_time')->default('06:00');
            $table->time('closing_time')->default('20:00');
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};