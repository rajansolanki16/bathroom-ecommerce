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
        Schema::create('blog_relation_with_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogs_id')->unsigned(); 
            $table->unsignedBigInteger('blog_categories_id')->unsigned(); 
            $table->foreign('blogs_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('blog_categories_id')->references('id')->on('blog_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_relation_with_categories');
    }
};
