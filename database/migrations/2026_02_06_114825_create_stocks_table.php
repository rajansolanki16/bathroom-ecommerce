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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            // product info
            $table->text('product_name');
            // $table->string('category')->nullable(); 
            // $table->string('brand')->nullable();
            $table->integer('quantity')->default(0);
            //$table->integer('min_quantity')->default(5);
            $table->string('unit')->default('pcs'); // pcs, box, set
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
