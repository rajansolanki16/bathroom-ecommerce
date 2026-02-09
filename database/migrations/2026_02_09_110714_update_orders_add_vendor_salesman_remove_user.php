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
        Schema::table('orders', function (Blueprint $table) {
            //  Remove old user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            //  Add new columns for vendor and salesman
            $table->foreignId('vendor_id')
                ->after('id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('salesman_id')
                ->after('vendor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //  Remove new columns
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['salesman_id']);
            $table->dropColumn(['vendor_id', 'salesman_id']);

            // Restore user_id
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }
};
