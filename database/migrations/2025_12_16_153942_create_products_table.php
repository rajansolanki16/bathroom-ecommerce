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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('product_title')->unique();
            $table->string('slug')->unique();
            $table->tinyInteger('product_type')->comment('0=simple,1=classified');//0=simple,1=classified
            $table->longText('short_description');
           // $table->string('brand')->nullable(); 
            $table->tinyInteger('exchangeable')->comment('0=no,1=yes')->default(0);
            $table->tinyInteger('refundable')->comment('0=no,1=yes')->default(0);
            $table->longText('product_decscription');
            $table->json('gallery_images')->nullable();
            // $table->string('manufacturer_name')->nullable();
            // $table->string('manufacturer_brand')->nullable();

            $table->integer('stock')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->tinyInteger('status')->comment('0=draft,1=published,2=scheduled')->default(1);//0=draft,1=published,2=scheduled
            $table->tinyInteger('visibility')->comment('0=hidden,1=visible')->default(1);//0=hidden,1=visible
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
