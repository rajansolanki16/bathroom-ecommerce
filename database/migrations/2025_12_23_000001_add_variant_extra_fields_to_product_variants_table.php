<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variants')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->after('product_id');
            }

            if (!Schema::hasColumn('product_variants', 'sell_price')) {
                $table->decimal('sell_price', 12, 2)->nullable()->after('price');
                $table->date('sell_price_start_date')->nullable()->after('sell_price');
                $table->date('sell_price_end_date')->nullable()->after('sell_price_start_date');
            }

            if (!Schema::hasColumn('product_variants', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('image')->comment('Weight in kg');
                $table->decimal('length', 8, 2)->nullable()->after('weight')->comment('Length in cm');
                $table->decimal('width', 8, 2)->nullable()->after('length')->comment('Width in cm');
                $table->decimal('height', 8, 2)->nullable()->after('width')->comment('Height in cm');
            }

            if (!Schema::hasColumn('product_variants', 'free_shipping')) {
                $table->tinyInteger('free_shipping')->default(0)->after('height')->comment('0=no,1=yes');
            }

            if (!Schema::hasColumn('product_variants', 'exchangeable')) {
                $table->tinyInteger('exchangeable')->default(0)->after('free_shipping')->comment('0=no,1=yes');
            }

            if (!Schema::hasColumn('product_variants', 'refundable')) {
                $table->tinyInteger('refundable')->default(0)->after('exchangeable')->comment('0=no,1=yes');
            }

            if (!Schema::hasColumn('product_variants', 'status')) {
                $table->tinyInteger('status')->default(1)->after('refundable')->comment('0=draft,1=published');
            }

            if (!Schema::hasColumn('product_variants', 'visibility')) {
                $table->tinyInteger('visibility')->default(1)->after('status')->comment('0=hidden,1=visible');
            }

            if (!Schema::hasColumn('product_variants', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping');
            }

            if (!Schema::hasColumn('product_variants', 'general_info')) {
                $table->longText('general_info')->nullable()->after('shipping_address');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_variants')) return;

        Schema::table('product_variants', function (Blueprint $table) {
            $columns = [
                'sku', 'sell_price', 'sell_price_start_date', 'sell_price_end_date',
                'weight','length','width','height','free_shipping','exchangeable','refundable','status','visibility','shipping_address','general_info'
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('product_variants', $col)) {
                    try {
                        $table->dropColumn($col);
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        });
    }
};
