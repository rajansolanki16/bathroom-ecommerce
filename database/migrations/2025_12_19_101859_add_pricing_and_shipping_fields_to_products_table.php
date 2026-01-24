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
        Schema::table('products', function (Blueprint $table) {

            // SKU
            $table->string('sku_number')->nullable()->after('product_title');

            // Selling price
            $table->decimal('sell_price', 10, 2)->nullable()->after('price');
            $table->date('sell_price_start_date')->nullable()->after('sell_price');
            $table->date('sell_price_end_date')->nullable()->after('sell_price_start_date');

            // Shipping details
            $table->decimal('weight', 8, 2)->nullable()->after('sell_price_end_date');
            $table->decimal('length', 8, 2)->nullable()->after('weight');
            $table->decimal('width', 8, 2)->nullable()->after('length');
            $table->decimal('height', 8, 2)->nullable()->after('width');

            // Free shipping option
            $table->tinyInteger('free_shipping')
                ->default(0)
                ->comment('0=no, 1=yes')
                ->after('height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku_number',
                'sell_price',
                'sell_price_start_date',
                'sell_price_end_date',
                'weight',
                'length',
                'width',
                'height',
                'free_shipping',
            ]);
        });
    }
};
