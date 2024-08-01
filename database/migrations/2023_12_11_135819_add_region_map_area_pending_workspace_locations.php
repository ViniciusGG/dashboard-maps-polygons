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
        Schema::table('workspace_area_points', function (Blueprint $table) {
            $table->json('region_map_area_pending')->nullable()->after('region_map_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_area_points', function (Blueprint $table) {
            $table->dropColumn('region_map_area_pending');
        });
    }
};
