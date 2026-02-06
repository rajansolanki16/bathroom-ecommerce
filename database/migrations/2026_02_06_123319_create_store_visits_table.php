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
        Schema::create('store_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->string('purpose');
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('outcome', ['positive', 'neutral', 'negative'])->default('neutral');
            $table->boolean('follow_up_required')->default(false);
            $table->date('next_follow_up_date')->nullable();
            $table->integer('rating')->nullable();
            $table->string('location_address')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_visits');
    }
};
