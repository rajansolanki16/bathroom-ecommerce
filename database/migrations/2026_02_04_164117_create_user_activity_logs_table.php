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
         Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('session_id');
            $table->timestamp('login_at')->useCurrent();
            $table->timestamp('last_activity_at')->useCurrent(); 
            $table->timestamp('logout_at')->nullable();
            $table->string('logout_reason')->nullable(); // 'manual', 'tab_closed', 'timeout'
            $table->timestamps();

            // Index for faster admin lookups
            $table->index(['user_id', 'logout_at']);
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
