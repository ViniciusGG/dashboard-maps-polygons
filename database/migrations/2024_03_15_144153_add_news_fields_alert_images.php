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
        Schema::table('alert_images', function (Blueprint $table) {
            $table->json('geo_coordinates')->nullable()->after('url');
            $table->integer('algorithm_type')->nullable()->after('geo_coordinates');
            $table->dropColumn('lat');
            $table->dropColumn('lng');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_images', function (Blueprint $table) {
            $table->dropColumn('geo_coordinates');
            $table->dropColumn('algorithm_type');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('type')->nullable();
        });
    }
};
