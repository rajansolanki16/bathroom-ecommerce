<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('offer_price')->nullable()->after('price');
            $table->string('tour_video')->nullable()->after('gallery_img');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'offer_price')) {
                $table->dropColumn('offer_price');
            }
            if (Schema::hasColumn('rooms', 'tour_video')) {
                $table->dropColumn('tour_video');
            }
        });
    }

};
