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
        Schema::table('store_visits', function (Blueprint $table) {
            //
            $table->boolean('is_approve')->nullable()->default(false)->after('location_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_visits', function (Blueprint $table) {
            //
            $table->dropColumn('is_approve');
        });
    }
};
