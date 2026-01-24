<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecommerce', function (Blueprint $table) {
            $table->id();
            $table->string('currency_symbol');
            $table->string('currency_word');
            $table->string('store_address');
            $table->string('store_city');

            $table->foreignId('store_country')
                ->constrained('countries')
                ->cascadeOnDelete();


            $table->integer('store_postal_code');
            $table->string('weight_unit');
            $table->string('dimension_unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce');
    }
};
