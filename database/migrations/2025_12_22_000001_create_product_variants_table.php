<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('sku')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->decimal('sell_price', 12, 2)->nullable();
                $table->string('shipping')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('variant_attribute_values')) {
            try {
                Schema::table('variant_attribute_values', function (Blueprint $table) {
                    if (!Schema::hasColumn('variant_attribute_values', 'product_variant_id')) return;
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
