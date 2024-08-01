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
        Schema::dropIfExists('workspace_locations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('workspace_locations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('workspace_id')->constrained('workspaces');
            $table->string('name')->nullable();
            $table->json('region_map_area')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
