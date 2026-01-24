<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_variant_attribute_value') && !Schema::hasTable('variant_attribute_values')) {
            Schema::create('product_variant_attribute_value', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
                $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['product_variant_id', 'attribute_value_id'], 'pv_av_unique');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('product_variant_attribute_value')) {
            Schema::dropIfExists('product_variant_attribute_value');
        }
    }
};
