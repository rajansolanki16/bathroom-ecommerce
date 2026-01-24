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
        Schema::create('abandoned_cart', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->dateTime('check_in')->nullable(false);
            $table->dateTime('check_out')->nullable(false);
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->string('room_id')->nullable(false);
            $table->integer('room_count')->default(0);
            $table->integer('extra_beds')->nullable(false)->default(0);
            $table->json('services')->default("[]");
            $table->integer('total_cost')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_cart');
    }
};
