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
        Schema::create('alert_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained('alerts');
            $table->string('type');
            $table->string('lat');
            $table->string('lng');
            $table->string('url');
            $table->timestamps();
        });

        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_images');

        Schema::table('alerts', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};
