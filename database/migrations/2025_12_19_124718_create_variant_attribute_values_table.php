<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create pivot table
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();

            if (Schema::hasTable('product_variants')) {
                $table->foreignId('product_variant_id')
                    ->constrained()
                    ->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('product_variant_id');
            }

            if (Schema::hasTable('attribute_values')) {
                $table->foreignId('attribute_value_id')
                    ->constrained()
                    ->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('attribute_value_id');
            }

            $table->unique(
                ['product_variant_id', 'attribute_value_id'],
                'variant_attr_val_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_values');
    }
};
