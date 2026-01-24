<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });

        $rooms = DB::table('rooms')->get();
        foreach ($rooms as $room) {
            $slug = Str::slug($room->name);

            $count = DB::table('rooms')->where('slug', 'like', "$slug%")->count();
            $finalSlug = $count ? "{$slug}-{$count}" : $slug;

            DB::table('rooms')->where('id', $room->id)->update(['slug' => $finalSlug]);
        }
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }                                                               
};
