<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('media_library_main_image_id')->nullable()->after('product_title');
            $table->json('media_library_gallery_image_ids')->nullable()->after('media_library_main_image_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['media_library_main_image_id', 'media_library_gallery_image_ids']);
        });
    }
};
