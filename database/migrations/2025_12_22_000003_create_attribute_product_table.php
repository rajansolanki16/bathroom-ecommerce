<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_attribute_id')->constrained('product_attribute')->onDelete('cascade');
            $table->unique(['product_id', 'product_attribute_id'], 'attr_prod_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_product');
    }
};
