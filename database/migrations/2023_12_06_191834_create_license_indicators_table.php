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
        Schema::create('license_indicators', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('license_id')->constrained('licenses');
            $table->foreignId('indicator_id')->constrained('indicators');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_indicators');
    }
};
